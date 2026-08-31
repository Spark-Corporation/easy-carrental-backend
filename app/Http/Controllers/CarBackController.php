<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\carBack;
use App\Http\Resources\CarBackResource;
use App\Http\Requests\StoreCarBackRequest;
use App\Models\Historic;
use App\Models\Setting;

class CarBackController extends Controller
{
    /**
     * Afficher la liste des retours de véhicules
     */
    public function index()
    {
        $carBacks = CarBack::with('reservation')->orderBy('created_at', 'desc')->paginate(15);
        return CarBackResource::collection($carBacks);
    }

    /**
     * Créer un retour de véhicule
     */
    public function store(StoreCarBackRequest $request)
    {
        $data = $request->validated();
        $carBack = CarBack::create($data);

        // Récupérer la réservation liée
        $reservation = $carBack->reservation;


        if ($reservation) {
            $reservation->status = 'Terminée';
            $reservation->dateBack = $carBack->returnDate;
            $reservation->save();

            // Mettre à jour la voiture liée
            $car = $reservation->car;
            if ($car) {
                $car->kilometrage = $carBack->returnKm;
                $car->niveauCarburant = $carBack->fluelLevel;
                $car->state = $carBack->state;
                $car->status = 'Disponible';
                $car->save();
            }

            if ($reservation->driver) {
                $reservation->driver->status = 'Disponible';
                $reservation->driver->save();
            }

            //Recalcul du montant basé sur dayAmount
            $startDate = \Carbon\Carbon::parse($reservation->dateStart);
            $endDate   = \Carbon\Carbon::parse($carBack->returnDate);
            $days = $startDate->diffInDays($endDate) ?: 1;

            $dayAmount = $reservation->car->dayAmount ?? 0;
            $baseAmount = $days * $dayAmount;

            $reductionRate   = Setting::get('reductionRate', 0);
            $tvaRate         = Setting::get('tvaRate', 0);
            $driverDailyRate = Setting::get('driverDailyRate', 0);

            $reductionAmount = $baseAmount * $reductionRate;
            $driverAmount    = $reservation->type === 'leasing' ? $driverDailyRate * $days : 0;

            $netPrice   = $baseAmount - $reductionAmount + $driverAmount;
            $tvaAmount  = $netPrice * $tvaRate;
            $totalAmount = $netPrice + $tvaAmount;

        
            // Mise à jour facture
            if ($reservation->invoice) {
                $reservation->invoice->update([
                    'driverAmount'    => $driverAmount,
                    'reductionAmount' => $reductionAmount,
                    'tvaAmount'       => $tvaAmount,
                    'totalAmount'     => $totalAmount,
                    'status'          => 'En attente',
                ]);
            }


            //Historique
            Historic::create([
                // 'reservation_id' => $reservation->id,
                'action'         => 'Retour du véhicule',
                'description'    => "Retour de la réservation #{$reservation->reservationNumber} : Km {$carBack->returnKm}, Carburant {$carBack->fluelLevel}, État {$carBack->state} , Montant recalculé {$totalAmount}",
                'user_id'        => Auth::id(),
                'actionDate'  => now(),
            ]);
        }

        return new CarBackResource($carBack);
    }

    /**
     * Afficher un retour de véhicule spécifique
     */
    public function show(int $id)
    {
        $carBack = CarBack::with('reservation')->findOrFail($id);
        return new CarBackResource($carBack);
    }

    /**
     * Mettre à jour un retour de véhicule
     */
    public function update(StoreCarBackRequest $request, int $id)
    {
        $carBack = CarBack::findOrFail($id);
        $carBack->update($request->validated());

        return new CarBackResource($carBack);
    }

    /**
     * Supprimer un retour de véhicule
     */
    public function destroy(int $id)
    {
        $carBack = CarBack::findOrFail($id);
        $carBack->delete();

        return response()->json(['message' => 'Retour de véhicule supprimé avec succès'], 200);
    }
}
