<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function () {

        // Message
    // Route::group(['prefix' => 'message' ], function () {
    //     Route::get('index', [App\Http\Controllers\WhatsAppController::class, 'index'])->name('message.index');
    //     Route::post('/sendMessageText', [App\Http\Controllers\WhatsAppController::class, 'sendMessageText'])->name('message.sendMessageText');
    //     Route::get('/OrderRejected', [App\Http\Controllers\WhatsAppController::class, 'OrderRejected'])->name('message.OrderRejected');
    //     Route::get('/OrderCancelledbyCustomer', [App\Http\Controllers\WhatsAppController::class, 'OrderCancelledbyCustomer'])->name('message.OrderCancelledbyCustomer');
    //     Route::get('/OrderDelivered', [App\Http\Controllers\WhatsAppController::class, 'OrderDelivered'])->name('message.OrderDelivered');
    //     Route::get('/OrderShipped', [App\Http\Controllers\WhatsAppController::class, 'OrderShipped'])->name('message.OrderShipped');
    //     Route::get('/CartAbandonment', [App\Http\Controllers\WhatsAppController::class, 'CartAbandonment'])->name('message.CartAbandonment');
    //     Route::get('/NewOrderReceived', [App\Http\Controllers\WhatsAppController::class, 'NewOrderReceived'])->name('message.NewOrderReceived');
    // });

        // Template
    // Route::group(['prefix' => 'template' ], function () {
    //     Route::get('index', [App\Http\Controllers\ContentTemplateController::class, 'Index'])->name('template.index');
    //     Route::get('create', [App\Http\Controllers\ContentTemplateController::class, 'create'])->name('template.create');
    //     Route::post('store', [App\Http\Controllers\ContentTemplateController::class, 'store'])->name('template.store');
    //     Route::get('edit/{id}', [App\Http\Controllers\ContentTemplateController::class, 'edit'])->name('template.edit');
    //     Route::put('update/{id}', [App\Http\Controllers\ContentTemplateController::class, 'update'])->name('template.update');
    //     Route::delete('destroy/{id}', [App\Http\Controllers\ContentTemplateController::class, 'destroy'])->name('template.destroy');
    // });

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');

        // Orders
    Route::group(['prefix' => 'orders' ], function () {
        Route::get('list', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
        Route::get('dukkan-orders-update/{days_limit}', [App\Http\Controllers\OrderController::class, 'dukkan_orders_update'])->name('orders.dukkan_orders_update');
        Route::get('woocommerce-orders-update/{days_limit}', [App\Http\Controllers\OrderController::class, 'woocommerce_orders_update'])->name('orders.woocommerce_orders_update');
        Route::get('invoice-pdf/{order_uuid}', [App\Http\Controllers\OrderController::class, 'orders_invoice_pdf'])->name('orders.invoice_pdf');
        Route::post('tracking-links', [App\Http\Controllers\OrderController::class, 'tracking_links'])->name('orders.tracking_links');
        Route::get('tracking-links/{orderId}', [App\Http\Controllers\OrderController::class, 'getTrackingLinks']);
        Route::post('shipping-label', [App\Http\Controllers\OrderController::class, 'shipping_label'])->name('orders.shipping_label');
        Route::post('/notes', [App\Http\Controllers\OrderController::class, 'addOrderNotes'])->name('orders.add_notes');
        Route::get('/notes/{orderId}', [App\Http\Controllers\OrderController::class, 'getOrderNotes'])->name('orders.get_notes');
        Route::get('shipping-label/{order_uuid}', [App\Http\Controllers\OrderController::class, 'shipping_label_pdf'])->name('orders.shipping_label_pdf');

    });

        // E-mail
    Route::group(['prefix' => 'email' ], function () {
        Route::get('index', [App\Http\Controllers\TestEmailController::class, 'index'])->name('email.index');
        Route::post('/sendMail', [App\Http\Controllers\TestEmailController::class, 'sendMail'])->name('email.sendMail');
    });

        // Pickings Products
    Route::group(['prefix' => 'products-picking' ], function () {
        Route::get('/', [App\Http\Controllers\ProductPickingController::class, 'index'])->name('products-picking.index');
        Route::post('filter', [App\Http\Controllers\ProductPickingController::class, 'filter'])->name('products-picking.filter');
    });

        // Packing  Products
    Route::group(['prefix' => 'products-packing' ], function () {
        Route::get('/', [App\Http\Controllers\PackingOrderProductController::class, 'index'])->name('products-packing.index');
        Route::get('/mark-product-packed', [App\Http\Controllers\PackingOrderProductController::class, 'MarkProductPacked'])->name('products-packing.mark-Pdt-packed');
        Route::get('/All-product-Packaged', [App\Http\Controllers\PackingOrderProductController::class, 'AllProductPackaged'])->name('products-packing.all-Pdt-packed');
        Route::get('/move-to-ship', [App\Http\Controllers\PackingOrderProductController::class, 'MoveToShip'])->name('products-packing.move-to-ship');
    });

        // Inventory 
    Route::group(['prefix' => 'inventory' ], function () {
        Route::get('/', [App\Http\Controllers\InventoryManagementController::class, 'Index'])->name('inventory.index');
        Route::get('create', [App\Http\Controllers\InventoryManagementController::class, 'create'])->name('inventory.create');
        Route::post('store', [App\Http\Controllers\InventoryManagementController::class, 'store'])->name('inventory.store');
        Route::get('edit/{id}', [App\Http\Controllers\InventoryManagementController::class, 'edit'])->name('inventory.edit');
        Route::put('update/{id}', [App\Http\Controllers\InventoryManagementController::class, 'update'])->name('inventory.update');
        Route::delete('destroy/{id}', [App\Http\Controllers\InventoryManagementController::class, 'destroy'])->name('inventory.destroy');
        Route::post('/update-status', [App\Http\Controllers\InventoryManagementController::class, 'updateStatus'])->name('inventory.updateStatus');
    });
    
        // Settings 
    Route::group(['prefix' => 'settings' ], function () {
        Route::get('/', [App\Http\Controllers\SiteSettingController::class, 'index'])->name('settings.index');
        Route::post('update', [App\Http\Controllers\SiteSettingController::class, 'update'])->name('settings.update');
        Route::post('credentials', [App\Http\Controllers\CredentialsController::class, 'update'])->name('credentials.index');
    });

        // Env Settings 
    Route::group(['prefix' => 'env-settings' ], function () {
        Route::post('update', [App\Http\Controllers\EnvSettingController::class, 'update'])->name('env_settings.Emailupdate');
        Route::post('shipping-update', [App\Http\Controllers\EnvSettingController::class, 'ShippingUpdate'])->name('env_settings.ShippingUpdate');
        Route::post('store-id-update', [App\Http\Controllers\EnvSettingController::class, 'StoreIDUpdate'])->name('env_settings.StoreIDUpdate');

        // Route::post('whatsapp-update', [App\Http\Controllers\EnvSettingController::class, 'WhatsAppUpdate'])->name('env_settings.WhatsAppUpdate');
    });
});