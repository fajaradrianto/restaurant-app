<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OrderController;

/*
|--------------------------------------------------------------------------
| Customer Routes (Akses via QR Code)
|--------------------------------------------------------------------------
*/
Route::prefix('order/{tableId}')->name('customer.')->group(function () {
    Route::get('/', [CustomerOrderController::class, 'showCustomerForm'])->name('form');
    Route::post('/customer', [CustomerOrderController::class, 'storeCustomer'])->name('store-customer');
    Route::get('/menu', [CustomerOrderController::class, 'showMenu'])->name('menu');
    Route::post('/add-to-cart', [CustomerOrderController::class, 'addToCart'])->name('add-to-cart');
    Route::post('/update-cart', [CustomerOrderController::class, 'updateCart'])->name('update-cart');
    Route::get('/checkout', [CustomerOrderController::class, 'showCheckout'])->name('checkout');
    Route::post('/process', [CustomerOrderController::class, 'processOrder'])->name('process');
    Route::get('/payment/{orderNumber}', [CustomerOrderController::class, 'showPayment'])->name('payment');
    Route::post('/confirm/{orderNumber}', [CustomerOrderController::class, 'confirmPayment'])->name('confirm');
    Route::get('/success/{orderNumber}', [CustomerOrderController::class, 'showSuccess'])->name('success');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Login / Logout (tanpa middleware)
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Routes yang butuh autentikasi
    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Manajemen Meja
        Route::resource('tables', TableController::class)->only(['index', 'store', 'update', 'destroy']);

        // Manajemen Menu
        Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
        Route::post('/menus/category', [MenuController::class, 'storeCategory'])->name('menus.store-category');
        Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
        Route::put('/menus/{id}', [MenuController::class, 'update'])->name('menus.update');
        Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->name('menus.destroy');

        // Manajemen Pesanan
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('/orders/{id}/print', [OrderController::class, 'print'])->name('orders.print');

        // Kitchen Display
        Route::get('/kitchen', [OrderController::class, 'kitchenDisplay'])->name('orders.kitchen');
        Route::post('/kitchen/update-status/{id}', [OrderController::class, 'kitchenUpdateStatus'])->name('orders.kitchen-update');
    });
});

// Redirect root ke admin
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});