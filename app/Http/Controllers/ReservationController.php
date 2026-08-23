<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Historic;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{

    // vérifie si une voiture est disponible sur une période donnée
    protected function getConflictingReservation(int $carId, string $dateStart, string $dateBack, ?int $excludeReservationId = null): ?Reservation
    {
        return Reservation::where('car_id', $carId)
            ->whereIn('status', ['Validée', 'En cours'])
            ->when($excludeReservationId, function ($query) use ($excludeReservationId) {
                $query->where('id', '!=', $excludeReservationId);
            })
            ->where(function ($query) use ($dateStart, $dateBack) {
                $query->whereBetween('dateStart', [$dateStart, $dateBack])
                    ->orWhereBetween('dateBack', [$dateStart, $dateBack])
                    ->orWhere(function ($q) use ($dateStart, $dateBack) {
                        $q->where('dateStart', '<=', $dateStart)
                            ->where('dateBack', '>=', $dateBack);
                    });
            })
            ->first();
    }
    // Liste des réservations
    public function index()
    {
        $reservations = Reservation::with(['user', 'car', 'driver', 'car.category', 'invoice'])->orderBy('created_at', 'desc')->paginate(15);
        return ReservationResource::collection($reservations);
    }


    public function store(StoreReservationRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        // Si c'est un client, on force son user_id
        if ($user->role === 'client') {
            $data['user_id'] = $user->id;
        }

        // Vérification disponibilité
        $conflict = $this->getConflictingReservation($data['car_id'], $data['dateStart'], $data['dateBack']);

        if ($conflict) {
            $dateStartFormatted = \Carbon\Carbon::parse($conflict->dateStart)->format('d/m/Y');
            $dateBackFormatted = \Carbon\Carbon::parse($conflict->dateBack)->format('d/m/Y');

            return response()->json([
                'message' => "Veuillez choisir une autre voiture, cette voiture est déjà réservée du {$dateStartFormatted} au {$dateBackFormatted}."
            ], 422);
        }

        // Si c'est un admin, on garde le user_id envoyé dans la requête
        $reservation = Reservation::create($data);

        if ($reservation->status === "En cours" && $reservation->car) {
            $reservation->startKm = $reservation->car->kilometrage;
            $reservation->save();
        }

        if ($reservation->status == "En cours") {
            $reservation->car->status = 'Louée';
        } elseif (in_array($reservation->status, ['Terminée', 'Annulée'])) {
            $reservation->car->status = 'Disponible';
        }
        $reservation->car->save();

        if ($reservation->driver) {
            if ($reservation->status == "En cours") {
                $reservation->driver->status = 'Affecté';
            } elseif (in_array($reservation->status, ['Terminée', 'Annulée'])) {
                $reservation->driver->status = 'Disponible';
            }
            $reservation->driver->save();
        }

        $invoice = $reservation->invoice()->create([
            'driverAmount'    => $reservation->driverAmount,
            'reductionAmount' => $reservation->reductionAmount ?? 0,
            'status'          => 'En attente',
        ]);

        //Historique
        Historic::create([
            // 'reservation_id' => $reservation->id,
            'action'         => 'réservation',
            'description'    => "Réservation #{$reservation->reservationNumber} enrégistrée pour la voiture #{$reservation->car_id}, du {$reservation->dateStart} au {$reservation->dateBack}, statut {$reservation->status}",
            'user_id'        => Auth::id(),
            'actionDate'  => now(),
        ]);

        return response()->json([
            'reservation' => new ReservationResource($reservation),
            'invoice' => $invoice,
        ], 201);
    }

    //Afficher une réservation
    public function show(Request $request, int $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }

        if ($user->role === 'admin') {
            // Admin : accès complet
            $reservation = Reservation::with(['user', 'car', 'driver', 'car.category', 'invoice'])
                ->findOrFail($id);
        } else {
            // Client : accès limité à ses réservations
            $reservation = Reservation::with(['user', 'car', 'driver', 'car.category', 'invoice'])
                ->where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();
        }

        return new ReservationResource($reservation);
    }

    // Mettre à jour une réservation
    public function update(StoreReservationRequest $request, int $id)
    {
        $reservation = Reservation::findOrFail($id);
        $data = $request->validated();
        // Vérification disponibilité (exclure la réservation en cours)
        $conflict = $this->getConflictingReservation($data['car_id'], $data['dateStart'], $data['dateBack'], $id);

        $statutsSansConflit = ['Annulée', 'Refusée'];

        if (!in_array($data['status'], $statutsSansConflit)) {
            $conflict = $this->getConflictingReservation(
                $data['car_id'],
                $data['dateStart'],
                $data['dateBack'],
                $id
            );

            if ($conflict) {
                $dateStartFormatted = \Carbon\Carbon::parse($conflict->dateStart)->format('d/m/Y');
                $dateBackFormatted = \Carbon\Carbon::parse($conflict->dateBack)->format('d/m/Y');

                return response()->json([
                    'message' => "Veuillez choisir une autre voiture, cette voiture est déjà réservée du {$dateStartFormatted} au {$dateBackFormatted}."
                ], 422);
            }
        }
        $reservation->update($data);

        if ($reservation->status === "En cours" && $reservation->car && !$reservation->startKm) {
            $reservation->startKm = $reservation->car->kilometrage;
            $reservation->save();
        }

        if ($reservation->status == "En cours") {
            $reservation->car->status = 'Louée';
        } elseif (in_array($reservation->status, ['Terminée', 'Annulée'])) {
            $reservation->car->status = 'Disponible';
        }
        $reservation->car->save();

        if ($reservation->driver) {
            if ($reservation->status == "En cours") {
                $reservation->driver->status = 'Affecté';
            } elseif (in_array($reservation->status, ['Terminée', 'Annulée'])) {
                $reservation->driver->status = 'Disponible';
            }
            $reservation->driver->save();
        }

        $invoice = $reservation->invoice;
        if ($invoice) {
            $invoice->update([
                'driverAmount'    => $reservation->driverAmount,
                'reductionAmount' => $reservation->reductionAmount ?? 0,
                'status'          => $invoice->status ?? "En attente",
            ]);
        }

        Historic::create([
            // 'reservation_id' => $reservation->id,
            'action'         => 'Mise à jour de réservation',
            'description'    => "Réservation #{$reservation->reservationNumber} mise à jour : voiture #{$reservation->car_id}, du {$reservation->dateStart} au {$reservation->dateBack}, statut {$reservation->status}",
            'user_id'        => Auth::id(),
            'actionDate'  => now(),
        ]);
        return response()->json([
            'reservation' => new ReservationResource($reservation),
            'invoice'     => $invoice
        ], 201);
    }

    // Supprimer une réservation
    public function destroy(int $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        Historic::create([
            'reservation_id' => $reservation->id,
            'action'         => 'Suppression de réservation',
            'description'    => "Réservation #{$reservation->reservationNumber} supprimée",
            'user_id'        => Auth::id(),
            'actionDate'  => now(),
        ]);
        return response()->json(['message' => 'Réservation supprimée avec succès'], 200);
    }


    public function estimate(StoreReservationRequest $request)
    {
        $data = $request->validated();

        $startDate = \Carbon\Carbon::parse($data['dateStart']);
        $endDate   = \Carbon\Carbon::parse($data['dateBack']);
        $days = $startDate->diffInDays($endDate);
        $days = round($days);

        $car = \App\Models\Car::findOrFail($data['car_id']);
        $dayAmount = $car->dayAmount ?? 0;

        $baseAmount = $days * $dayAmount;

        $reductionRate = \App\Models\Setting::get('reductionRate', 0);
        $tvaRate       = \App\Models\Setting::get('tvaRate', 0);
        $driverDailyRate = Setting::get('driverDailyRate', 0);

        $reductionAmount = $baseAmount * $reductionRate;

        if ($data['type'] === 'leasing') {
            $driverAmount    = $driverDailyRate * $days;
        } else {
            $driverAmount = 0;
        }

        $netPrice   = $baseAmount - $reductionAmount + $driverAmount;
        $tvaAmount  = $netPrice * $tvaRate;
        $totalAmount = $netPrice + $tvaAmount;

        return response()->json([
            'days'            => $days,
            'driverDailyRate' => round($driverDailyRate, 2),
            'carAmount'       => round($baseAmount, 2),
            'reductionAmount' => round($reductionAmount, 2),
            'driverAmount'    => round($driverAmount, 2),
            'tvaAmount'       => round($tvaAmount, 2),
            'totalAmount'     => round($totalAmount, 2),
        ]);
    }

    public function myReservations(Request $request)
    {
        $user = $request->user();

        // Récupérer toutes les réservations de l’utilisateur connecté
        $reservations = Reservation::with(['car', 'driver', 'user', 'invoice', 'car.category'])
            ->where('user_id', $user->id)
            ->orderBy('dateStart', 'asc')
            ->get();

        // Retourner via Resource
        return ReservationResource::collection($reservations);
    }

    public function cancel(Request $request, int $id)
    {
        $reservation = Reservation::findOrFail($id);

        // Vérifier que l'utilisateur connecté est bien propriétaire
        $user = $request->user();
        if (!$user || $reservation->user_id !== $user->id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $reservation->status = 'Annulée';
        $reservation->save();
        Historic::create([
            // 'reservation_id' => $reservation->id,
            'action'         => 'Annulation de réservation',
            'description'    => "Réservation #{$reservation->reservationNumber} annulée par le client",
            'user_id'        => Auth::id(),
            'actionDate'  => now(),
        ]);

        if ($reservation->driver) {
            $reservation->driver->status = 'Disponible';
            $reservation->driver->save();
        }

        return new ReservationResource($reservation);
    }
}
