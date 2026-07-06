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
        $drivers = Driver::orderBy('created_at','desc')->paginate(10);
        return DriverResource::collection($drivers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDriverRequest $request)
    {
        $driver = Driver::create($request->validated());
        return new DriverResource($driver);
    }

    /**
     * Display the specified resource.
     */

    public function show(int $id)
    {
        $driver = Driver::findOrFail($id);
        return new DriverResource($driver);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StoreDriverRequest $request, int $id)
    {
        $driver = Driver::findOrFail($id);
        $driver->update($request->validated());
        return new DriverResource($driver);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $driver = Driver::findOrFail($id);
        $driver->delete();
        return response()->Json(['message'=>'Chauffeur supprimé'],200);
    }
}
