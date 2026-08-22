<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;

use App\Http\Requests\StoreCategoryRequest;

use App\Models\Category as ModelsCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Historic;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$categories = ModelsCategory::latest()->paginate(10);
        $categories = ModelsCategory::where('active', true)->orderBy('created_at', 'desc')->paginate(10);
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = ModelsCategory::create($request->validated());

        //Historique
        Historic::create([
            'action'      => 'Enrégistrement de catégorie',
            'description' => "Catégorie #{$category->id} créée : {$category->name}",
            'user_id'     => Auth::id(),
           'actionDate'  => now(),
        ]);
        return new CategoryResource($category);
    }
    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
         $category = ModelsCategory::where('id', $id)
                ->where('active', true)
                ->firstOrFail();
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCategoryRequest $request, int $id)
    {
        $category = ModelsCategory::findOrFail($id);
        $category->update($request->validated());

        //Historique
        Historic::create([
            'action'      => 'Mise à jour de catégorie',
            'description' => "Catégorie #{$category->id} mise à jour : {$category->name}",
            'user_id'     => Auth::id(),
            'actionDate'  => now(),
        ]);
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $category = ModelsCategory::findOrFail($id);
        $category->active = false;
        $category->save();
        //Historique
        Historic::create([
            'action'      => 'Suppression de catégorie',
            'description' => "Catégorie #{$category->id} supprimée (désactivée) : {$category->name}",
            'user_id'     => Auth::id(),
            'actionDate'  => now(),
        ]);
        return response()->json(['message'=>'categorie supprimé'],200);
    }
}
