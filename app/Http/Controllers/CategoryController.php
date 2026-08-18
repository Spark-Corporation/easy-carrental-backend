<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;

use App\Http\Requests\StoreCategoryRequest;

use App\Models\Category as ModelsCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        return response()->json(['message'=>'categorie supprimé'],200);
    }
}
