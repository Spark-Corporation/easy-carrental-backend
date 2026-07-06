<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\category;
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
        //$categories = Category::latest()->paginate(10);
        $categories = Category::orderBy('created_at', 'desc')->paginate(10);
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return new CategoryResource($category);
    }
    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $category = Category::findOrFail($id);
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCategoryRequest $request, int $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message'=>'categorie supprimé'],200);
    }
}
