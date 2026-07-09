<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;

class ReservationController extends Controller
{
    // Liste des réservations
    public function index()
    {
        $reservations = Reservation::with(['client','car'])->orderBy('created_at','desc')->paginate(15);
        return ReservationResource::collection($reservations);
    }

    // Créer une réservation
    public function store(StoreReservationRequest $request)
    {
        $reservation = Reservation::create($request->validated());
        return new ReservationResource($reservation);
    }

    // Afficher une réservation
    public function show(int $id)
    {
        $reservation = Reservation::with(['client','car'])->findOrFail($id);
        return new ReservationResource($reservation);
    }

    // Mettre à jour une réservation
    public function update(StoreReservationRequest $request, int $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->validated());
        return new ReservationResource($reservation);
    }

    // Supprimer une réservation
    public function destroy(int $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return response()->json(['message' => 'Réservation supprimée avec succès'],200);
    }
}
