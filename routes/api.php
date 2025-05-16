<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderController;

// Public routes
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);

    // Customer routes
    Route::apiResource('customers', CustomerController::class);
    Route::get('/customers/{customer}/orders/largest', [CustomerController::class, 'largestOrder']);
    Route::get('/customers/{customer}/orders/first', [CustomerController::class, 'firstOrder']);
    Route::get('/customers/{customer}/orders/latest', [CustomerController::class, 'latestOrder']);

    // Table routes
    Route::apiResource('tables', TableController::class);

    // Menu routes
    Route::apiResource('menu/categories', MenuCategoryController::class);
    Route::apiResource('menu/items', MenuItemController::class);

    // Order routes
    Route::get('/orders/waiters', [OrderController::class,'waiterOrders']);
    Route::get('/orders/cooks', [OrderController::class,'cookOrders']);
    Route::apiResource('orders', OrderController::class);
});