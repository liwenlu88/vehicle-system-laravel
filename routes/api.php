<?php

use Illuminate\Support\Facades\Route;

Route::post('auth/login', [\App\Http\Controllers\AuthController::class, 'login'])
    ->middleware(\App\Http\Middleware\OperationLog::class);

Route::middleware([
    'auth:sanctum',
    \App\Http\Middleware\OperationLog::class
])->group(function () {
    Route::post('auth/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

    Route::middleware([
        \App\Http\Middleware\CheckPermission::class
    ])->group(function () {
        // 用户管理
        Route::get('users/options', [\App\Http\Controllers\UserController::class, 'options']);
        Route::resource('users', \App\Http\Controllers\UserController::class);

        Route::resource('roles', \App\Http\Controllers\RoleController::class);
    });
});