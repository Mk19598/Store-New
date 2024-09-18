<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Orders
Route::get('/orders-list', [App\Http\Controllers\OrderController::class, 'order_list'])->name('orders.list');
Route::get('/orders-store', [App\Http\Controllers\OrderController::class, 'orders_store'])->name('orders.store');
Route::get('/order-view/{id}', [App\Http\Controllers\OrderController::class, 'order_view'])->name('orders.view');