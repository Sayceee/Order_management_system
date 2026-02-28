<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;

// API Routes (for AJAX calls)
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::post('/orders', [OrderController::class, 'store']);
Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);