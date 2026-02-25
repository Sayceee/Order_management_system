<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\MpesaController;
use App\Http\Controllers\Api\InvoiceController;

// API routes (all will start with /api/)
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::post('/orders', [OrderController::class, 'store']);
Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);

// Allow PUT/PATCH to /api/orders/{id} as an alias for updating status
Route::match(['put', 'patch'], '/orders/{id}', [OrderController::class, 'updateStatus']);

// Mpesa integration routes
Route::post('/mpesa/stkpush', [MpesaController::class, 'stkPush']);
Route::post('/mpesa/callback', [MpesaController::class, 'callback']);

// Temporary OAuth test route
Route::get('/mpesa/token', function () {
    $res = \Illuminate\Support\Facades\Http::withoutVerifying()
        ->withBasicAuth(env('MPESA_CONSUMER_KEY'), env('MPESA_CONSUMER_SECRET'))
        ->get('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
    return $res->json();
});

Route::get('/invoice/{orderId}', [InvoiceController::class, 'generateInvoice']);
Route::get('/track/{orderId}', [InvoiceController::class, 'trackOrder']);