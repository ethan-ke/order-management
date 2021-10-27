<?php

use App\Http\Controllers\Admin\AuthorizationsController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\MerchantsController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\QueryLogsController;
use App\Http\Controllers\Admin\UsersController;

Route::post('authorizations', [AuthorizationsController::class, 'store'])
    ->name('authorizations.store');
Route::middleware('auth:admin-api')->group(function() {
    // 当前登录用户信息
    Route::get('user', [UsersController::class, 'mine']);
    Route::get('statistics', [UsersController::class, 'statistics']);
    Route::get('query-logs', [QueryLogsController::class, 'index']);
    Route::resource('orders', OrdersController::class);
    Route::resource('merchants', MerchantsController::class);
    Route::resource('customers', CustomerController::class);
    Route::patch('customers/items/bulk-update', [CustomerController::class, 'bulkUpdate']);
});
