<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Orders

    Route::group(['prefix' => 'orders' ], function () {
        Route::get('list', [App\Http\Controllers\OrderController::class, 'order_list'])->name('orders.list');
        Route::get('store', [App\Http\Controllers\OrderController::class, 'orders_store'])->name('orders.store');
    });
});