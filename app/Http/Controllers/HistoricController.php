<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHistoricRequest;
use App\Http\Resources\HistoricResource;
use App\Models\Historic;
use Illuminate\Http\Request;

class HistoricController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $historics = Historic::with('user')->orderBy('created_at','desc')->paginate();
        return HistoricResource::collection($historics);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHistoricRequest $request)
    {
        $historic = Historic::create($request->validated());
        return new HistoricResource($historic);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $historic = Historic::with('user')->findOrFail($id);
        return new HistoricResource($historic);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreHistoricRequest $request, int $id)
    {
        $historic = Historic::findOrFail($id);
        $historic->update($request->validated());
        return new HistoricResource($historic);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $historic = Historic::findOrFail($id);
        $historic->delete();
        return response()->json(["message"=>"Historique supprimé",200]);
    }
}
