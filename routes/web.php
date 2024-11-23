<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');

        // Orders
    Route::group(['prefix' => 'orders' ], function () {
        Route::get('list', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
        Route::get('store-update', [App\Http\Controllers\OrderController::class, 'orders_store'])->name('orders.store');
        Route::get('receipt-pdf/{order_uuid}', [App\Http\Controllers\OrderController::class, 'orders_receipt_pdf'])->name('orders.receipt_pdf');
        Route::post('tracking-links', [App\Http\Controllers\OrderController::class, 'tracking_links'])->name('orders.tracking_links');
        Route::get('tracking-links/{orderId}', [App\Http\Controllers\OrderController::class, 'getTrackingLinks']);
        Route::post('shipping-lable', [App\Http\Controllers\OrderController::class, 'shipping_lable'])->name('orders.shipping_lable');
    });

        // E-mail
    Route::group(['prefix' => 'email' ], function () {
        Route::get('index', [App\Http\Controllers\TestEmailController::class, 'index'])->name('email.index');
        Route::post('/sendMail', [App\Http\Controllers\TestEmailController::class, 'sendMail'])->name('email.sendMail');
    });

        // Message
    Route::group(['prefix' => 'message' ], function () {
        Route::get('index', [App\Http\Controllers\WhatsAppController::class, 'index'])->name('message.index');
        Route::post('/sendMessageText', [App\Http\Controllers\WhatsAppController::class, 'sendMessageText'])->name('message.sendMessageText');
    });

        // Pickings Products
    Route::group(['prefix' => 'picking' ], function () {
        Route::get('warehouse-products', [App\Http\Controllers\ProductPickingController::class, 'index'])->name('picking.products.index');
        Route::post('warehouse-products-filter', [App\Http\Controllers\ProductPickingController::class, 'filter'])->name('picking.products.filter');
    });

        // Packing Orders
    Route::group(['prefix' => 'products' ], function () {
        // Route::get('orders-packing', [App\Http\Controllers\ProductController::class, 'warehouse_pickings'])->name('products.warehouse_pickings');
    });

        // Inventory 
    Route::group(['prefix' => 'inventory' ], function () {
        Route::get('index', [App\Http\Controllers\InventoryManagementController::class, 'Index'])->name('inventory.index');
        Route::get('create', [App\Http\Controllers\InventoryManagementController::class, 'create'])->name('inventory.create');
        Route::post('store', [App\Http\Controllers\InventoryManagementController::class, 'store'])->name('inventory.store');
        Route::get('edit/{id}', [App\Http\Controllers\InventoryManagementController::class, 'edit'])->name('inventory.edit');
        Route::put('update/{id}', [App\Http\Controllers\InventoryManagementController::class, 'update'])->name('inventory.update');
        Route::delete('destroy/{id}', [App\Http\Controllers\InventoryManagementController::class, 'destroy'])->name('inventory.destroy');
    });
    
        // Settings 
    Route::group(['prefix' => 'settings' ], function () {
        Route::get('index', [App\Http\Controllers\SiteSettingController::class, 'index'])->name('settings.index');
        Route::post('update', [App\Http\Controllers\SiteSettingController::class, 'update'])->name('settings.update');
        Route::post('cerenditals', [App\Http\Controllers\CerenditalsController::class, 'update'])->name('cerenditals.index');
    });

        // Shipping
    Route::group(['prefix' => 'shipping' ], function () {
        Route::get('label', [App\Http\Controllers\ShippingManagementController::class, 'label'])->name('shipping.label');
    });
});