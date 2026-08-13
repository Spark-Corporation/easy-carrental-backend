<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use Illuminate\Http\Request;
use App\Http\Resources\CarResource;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Car::where('active', true)->with('category')->OrderBy('created_at', 'desc')->paginate(15);
        return CarResource::collection($cars);
    }

    public function availableCars()
    {
        $cars = Car::with('category')
            ->where('status', 'Disponible')
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return CarResource::collection($cars);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request)
    {
        // Récupère les données validées
        $validated = $request->validated();
        // Vérifie si une photo est envoyée
        if ($request->hasFile('photo')) {
            // Stocke l'image dans storage/app/public/cars
            $path = $request->file('photo')->store('cars', 'public');
            // Sauvegarde le chemin dans la base
            $validated['photo'] = $path;
            // $data['photo'] = $path;
        }

        // Crée la voiture avec les données validées
        $car = Car::create($validated);
        return new CarResource($car);
    }


    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $car = Car::where('id', $id)->with('category')
                ->where('active', true)
                ->firstOrFail();
        return new CarResource($car);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarRequest $request, int $id)
    {
        $car = Car::findOrFail($id);

        $validated = $request->validated();

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('cars', 'public');
            $validated['photo'] = $path;
        }

        $car->update($validated);

        return new CarResource($car);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $car = Car::findOrFail($id);

        if (!$car) {
            return response()->json(['message' => 'Voiture introuvable'], 404);
        }

        // Vérifier si la voiture est louée
        $hasActiveReservation = $car->reservations()
            ->whereIn('status', ['En cours', 'Validée'])
            ->exists();

        if ($hasActiveReservation) {
            return response()->json([
                'message' => 'Impossible de supprimer : la voiture est actuellement louée.'
            ], 400);
        }

        // Mettre la voiture en inactive
        $car->active = false;
        $car->save();
        return response()->Json(["message" => 'voiture supprimée'], 200);
    }
}
