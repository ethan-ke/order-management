<?php

use App\Http\Controllers\Merchant\AuthorizationsController;
use App\Http\Controllers\Merchant\OrdersController;
use App\Http\Controllers\Merchant\UsersController;

Route::post('authorizations', [AuthorizationsController::class, 'store'])
    ->name('authorizations.store');
Route::middleware('auth:merchant-api')->group(function() {
    // 当前登录用户信息
    Route::get('user', [UsersController::class, 'mine']);
    Route::patch('orders/status/{order}', [OrdersController::class, 'cancel']);
    Route::resource('orders', OrdersController::class);
    Route::get('statistics', [OrdersController::class, 'statistics']);
});
