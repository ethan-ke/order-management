<?php

use App\Http\Controllers\Merchant\AuthorizationsController;
use App\Http\Controllers\Merchant\CustomerController;
use App\Http\Controllers\Merchant\OrdersController;
use App\Http\Controllers\Merchant\UsersController;

Route::post('authorizations', [AuthorizationsController::class, 'store']);
Route::delete('authorizations/current', [AuthorizationsController::class, 'destroy']);
Route::middleware('auth:merchant-api')->group(function() {
    // 当前登录用户信息
    Route::get('user', [UsersController::class, 'mine']);
    Route::patch('orders/status/{order}', [OrdersController::class, 'cancel']);
    Route::resource('orders', OrdersController::class);
    Route::get('statistics', [OrdersController::class, 'statistics']);
    Route::get('search/phone', [CustomerController::class, 'search']);
    Route::post('search/photo', [CustomerController::class, 'photo']);
    Route::patch('password', [UsersController::class, 'password']);
    Route::post('report', [CustomerController::class, 'report']);
    Route::post('report/number', [CustomerController::class, 'number']);
    Route::resource('customers', CustomerController::class);
});
