<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;

// API routes (all will start with /api/)
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::post('/orders', [OrderController::class, 'store']);
Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);

// Allow PUT/PATCH to /api/orders/{id} as an alias for updating status
Route::match(['put', 'patch'], '/orders/{id}', [OrderController::class, 'updateStatus']);