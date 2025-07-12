<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
  
    public function index()
    {
        $categories = Category::withCount('products')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => CategoryResource::collection($categories)
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {

        $category = Category::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'data' => new CategoryResource($category) 
        ], 201);
    }

    public function show(Category $category)
    {
        $category->load('products');
        
        return response()->json([
            'status' => 'success',
            'data' => new CategoryResource($category)
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {

        $category->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully',
            'data' => new CategoryResource($category)
        ]);
    }

    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete category with existing products'
            ], 400);
        }

        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully'
        ]);
    }
}
