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

    Route::group(['prefix' => 'email' ], function () {
        Route::get('index', [App\Http\Controllers\TestEmailController::class, 'index'])->name('email.index');
        Route::post('/sendMail', [App\Http\Controllers\TestEmailController::class, 'sendMail'])->name('email.sendMail');
    });


    Route::group(['prefix' => 'message' ], function () {
        Route::get('index', [App\Http\Controllers\WhatsAppController::class, 'index'])->name('message.index');
        Route::post('/sendMessageText', [App\Http\Controllers\WhatsAppController::class, 'sendMessageText'])->name('message.sendMessageText');
    });

});