<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\MpesaController;
use App\Http\Controllers\CartController;

// LANDING PAGE - Beautiful welcome
Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// STOREFRONT - Shopping page (requires login)
Route::get('/shop', function () {
    return view('storefront');
})->middleware(['auth'])->name('shop');

// CREATE ORDER - Form submits here
Route::post('/api/orders', [OrderController::class, 'store'])->name('orders.store');

// M-PESA CALLBACK - For payment confirmation
Route::post('/mpesa/callback', [MpesaController::class, 'callback'])->name('mpesa.callback');

// TRACKING PAGE - View order status
Route::get('/track/{orderId}', [InvoiceController::class, 'trackOrder'])->name('track.order');

// INVOICE PDF - Download invoice
Route::get('/invoice/{orderId}', [InvoiceController::class, 'generateInvoice'])->name('invoice.generate');

// PAYMENT SUCCESS - After M-Pesa
Route::get('/order_success/{order_id}', function($order_id) {
    return view('order_success', ['order_id' => $order_id]);
})->name('order.success');

// PROFILE ROUTES (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Cart routes
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

require __DIR__.'/auth.php';