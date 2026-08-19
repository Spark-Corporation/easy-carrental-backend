<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\carBack;
use App\Http\Resources\CarBackResource;
use App\Http\Requests\StoreCarBackRequest;

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
