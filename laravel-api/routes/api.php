<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


// Categories
Route::apiResource('categories', CategoryController::class);

// Products
Route::apiResource('products', ProductController::class);