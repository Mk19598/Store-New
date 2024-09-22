<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');

    // Orders

    Route::group(['prefix' => 'orders' ], function () {
        Route::get('index', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
        Route::get('store', [App\Http\Controllers\OrderController::class, 'orders_store'])->name('orders.store');
        Route::get('receipt-pdf/{order_uuid}', [App\Http\Controllers\OrderController::class, 'orders_receipt_pdf'])->name('orders.receipt_pdf');
    });
});