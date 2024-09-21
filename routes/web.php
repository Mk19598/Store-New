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
        Route::get('index', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
        Route::get('store', [App\Http\Controllers\OrderController::class, 'orders_store'])->name('orders.store');
    });

    Route::group(['prefix' => 'email' ], function () {
        Route::get('index', [App\Http\Controllers\TestEmailController::class, 'index'])->name('email.index');
        Route::post('/sendMail', [App\Http\Controllers\TestEmailController::class, 'sendMail'])->name('email.sendMail');
    });


    Route::group(['prefix' => 'message' ], function () {
        Route::get('index', [App\Http\Controllers\WhatsAppController::class, 'index'])->name('message.index');
        Route::post('/sendMessageText', [App\Http\Controllers\WhatsAppController::class, 'sendMessageText'])->name('message.sendMessageText');
    });

});