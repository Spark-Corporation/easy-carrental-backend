<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
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
        $cars = Car::with('category')->OrderBy('created_at','desc')->paginate(15);
        return CarResource::collection($cars);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request)
    {
        $car = Car::create($request->validated());
        return new CarResource($car);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $car = Car::with('category')->findOrFail($id);
        return new CarResource($car);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $car = Car::findOrFail($id);

        $validated = $request->validate([
        'mark'=>'string|required|max:255',
        'type'=>'string|required|max:255',
        'model'=>'string|required|max:255',
        'color'=>'string|required|max:255',
        'photo'=>'string|required|max:255',
        'imatriculation'  => 'string|required|max:255|unique:cars,imatriculation,' . $id,
        'description'=>'string|max:255',
        'prix_km'=>'numeric|required|min:0',
        'state'=>'string|required|max:255',
        'place'=>'numeric|required|min:0',
        'door'=>'numeric|required|min:0',
        'kilometrage'=>'numeric|required|min:0',
        'niveauCarburant'=>'numeric|required|min:0',
        'domage'=>'string|max:255',
        'category_id' => 'required|exists:categories,id',
    ]);

        $car->update($validated);

        return new CarResource($car);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $car = Car::findOrFail($id);
        $car->delete();
        return response()->Json(["message"=>'voiture supprimée'],200);
    }
}
