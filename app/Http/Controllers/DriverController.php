<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDriverRequest;
use App\Http\Resources\DriverResource;
use App\Models\Driver;
use Illuminate\Http\Request;
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
        return response()->Json(['message' => 'Chauffeur supprimé'], 200);
    }
}
