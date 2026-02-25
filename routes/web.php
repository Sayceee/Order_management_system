<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::get('/', function () {
    return view('welcome');
});

// ADD THESE TWO LINES - They link your browser to your Controller
Route::get('/invoice/{orderId}', [InvoiceController::class, 'generateInvoice']);
Route::get('/track/{orderId}', [InvoiceController::class, 'trackOrder']);
Route::view('/storefront', 'storefront');
Route::get('/order-success/{order_id}', function ($order_id) {
    return view('order_success', ['order_id' => $order_id]);
})->name('order.success');