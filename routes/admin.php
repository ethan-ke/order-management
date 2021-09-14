<?php

use App\Http\Controllers\Admin\AuthorizationsController;
use App\Http\Controllers\Admin\MerchantsController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\UsersController;

Route::post('authorizations', [AuthorizationsController::class, 'store'])
    ->name('authorizations.store');
Route::middleware('auth:admin-api')->group(function() {
    // 当前登录用户信息
    Route::get('user', [UsersController::class, 'mine']);
    Route::get('statistics', [UsersController::class, 'statistics']);
    Route::resource('orders', OrdersController::class);
    Route::resource('merchants', MerchantsController::class);
});
