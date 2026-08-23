<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDriverRequest;
use App\Http\Resources\DriverResource;
use App\Http\Resources\DriverHistoricResource;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Historic;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = Driver::where('active', true)->orderBy('created_at', 'desc')->paginate(10);
        return DriverResource::collection($drivers);
    }

    public function availableDrivers()
    {
        $drivers = Driver::query()
            ->where('status', 'Disponible')
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return DriverResource::collection($drivers);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDriverRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('drivers', 'public');
            $data['photo'] = $path;
        }
        $driver = Driver::create($data);

        //Historique
        Historic::create([
            'action'      => 'Création de conducteur',
            'description' => "conducteur #{$driver->id} créé : {$driver->nom} {$driver->prenom}, téléphone {$driver->telephone}",
            'user_id'     => Auth::id(),
            'actionDate'  => now(),
        ]);
        return new DriverResource($driver);
    }

    /**
     * Display the specified resource.
     */

    public function show(int $id)
    {
        $driver = driver::where('id', $id)
            ->where('active', true)
            ->firstOrFail();
        return new DriverResource($driver);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StoreDriverRequest $request, int $id)
    {
        $driver = Driver::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('drivers', 'public');
            $validated['photo'] = $path;
        }
        $driver->update($validated);
        //Historique
        Historic::create([
            'action'      => 'Mise à jour de conducteur',
            'description' => "Conducteur #{$driver->id} mis à jour : {$driver->lastname} {$driver->firstname}, téléphone {$driver->telephone}",
            'user_id'     => Auth::id(),
            'actionDate'  => now(),
        ]);


        return new DriverResource($driver);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $driver = Driver::findOrFail($id);
        $driver->active = false;
        $driver->save();

        //Historique
        Historic::create([
            'action'      => 'Suppression de chauffeur',
            'description' => "Chauffeur #{$driver->id} supprimé (désactivé) : {$driver->nom} {$driver->prenom}, téléphone {$driver->telephone}",
            'user_id'     => Auth::id(),
            'actionDate'  => now(),
        ]);
        return response()->Json(['message' => 'Chauffeur supprimé'], 200);
    }

    public function allHistoric()
    {
        $drivers = Driver::where('active', true)
            ->with(['reservations.car', 'reservations.user', 'reservations.carBack', 'reservations.driver'])
            ->get();

        return $drivers->map(function ($driver) {
            return [
                'driver' => [
                    'id'     => $driver->id,
                    'lastname'    => $driver->lastname,
                    'firstname' => $driver->firstname,
                ],
                'reservations' => DriverHistoricResource::collection($driver->reservations),
            ];
        });
    }
}
