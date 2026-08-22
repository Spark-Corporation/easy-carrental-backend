<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePanneRequest ;
use App\Models\Panne;
use Illuminate\Http\Request;
use App\Http\Resources\PanneResource;
use Illuminate\Support\Facades\Auth;
use  App\Models\Historic;

class PanneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pannes = Panne::with('car')->orderBy('created_at','desc')->paginate(15);
        return PanneResource::collection($pannes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePanneRequest $request)
    {
        $panne = Panne::create($request->validated());

        //Mettre à jour le statut de la voiture associée
        $car = $panne->car;
        if ($car) {
            $car->status = 'En panne';
            $car->save();
        }
        // Historique
        Historic::create([
            'action'      => 'Enrégistrement de panne',
            'description' => "Panne #{$panne->id} enregistrée pour la voiture #{$car->id} : {$panne->description}, priorité {$panne->priority}",
            'user_id'     => Auth::id(),
           'actionDate'  => now(),
        ]);
        return new PanneResource($panne);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $panne = Panne::with('car')->findOrFail($id);
        return new PanneResource($panne);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePanneRequest $request, int $id)
    {
        $panne = Panne::findOrFail($id);
        $panne->update($request->validated());

        if ($panne->status === 'Réparée' && $panne->car) {
            $panne->car->status = 'Disponible';
            $panne->car->save();
        }
        //Historique
        Historic::create([
            'action'      => 'Mise à jour de panne',
            'description' => "Panne #{$panne->id} mise à jour pour la voiture #{$panne->car->id} : statut {$panne->status}, montant {$panne->panneAmount}",
            'user_id'     => Auth::id(),
            'actionDate'  => now(),
        ]);
        return new PanneResource($panne);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $panne = Panne::findOrFail($id);
        $panne->delete();
        //Historique
        Historic::create([
            'action'      => 'Suppression de panne',
            'description' => "Panne #{$panne->id} supprimée pour la voiture #{$panne->car_id}",
            'user_id'     => Auth::id(),
            'actionDate'  => now(),
        ]);
        return response()->Json(['message'=>'panne supprimé'],200);
    }
}
