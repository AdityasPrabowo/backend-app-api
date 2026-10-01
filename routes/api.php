<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// 1. Tes Route Langsung (Bypass Controller)
Route::get('/products-test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Route API Vercel Berhasil Terhubung!'
    ]);
});

// 2. Resource Controller Kamu
Route::apiResource('products', ProductController::class);