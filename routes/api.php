<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route standar Laravel (/api/products)
Route::apiResource('products', ProductController::class);

// Fallback khusus Vercel routing
Route::apiResource('api/products', ProductController::class);