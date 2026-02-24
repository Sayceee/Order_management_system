<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/invoice/{orderId}', [InvoiceController::class, 'generateInvoice']);
Route::get('/track/{orderId}', [InvoiceController::class, 'trackOrder']);