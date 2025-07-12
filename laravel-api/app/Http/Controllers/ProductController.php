<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter by category
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Search by name
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort by price
        if ($request->has('sort_by') && $request->sort_by == 'price') {
            $direction = $request->get('sort_direction', 'asc');
            $query->orderBy('price', $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $products = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' =>  new ProductCollection($products) ,
        ]);
    }

    public function store(StoreProductRequest $request)
    {


        $product = Product::create($request->all());
        $product->load('category');

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => new ProductResource($product) 
        ], 201);
    }

    public function show(Product $product)
    {
        $product->load('category');
        
        return response()->json([
            'status' => 'success',
            'data' =>  new ProductResource($product) 
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {

        $product->update($request->all());
        $product->load('category');

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
            'data' => new ProductResource($product) 
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully'
        ]);
    }



    
}
