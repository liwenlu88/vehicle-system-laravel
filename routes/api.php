<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\OperationLog;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthController::class, 'login'])
    ->middleware(OperationLog::class);

Route::middleware([
    'auth:sanctum',
    OperationLog::class // 操作日志
])->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);

    Route::middleware([
        \App\Http\Middleware\CheckPermission::class // 权限检查
    ])->group(function () {
        // 用户管理
        Route::get('users/options', [UserController::class, 'options']);
        Route::resource('users', UserController::class);

        // 角色管理
        Route::resource('roles', RoleController::class);
    });
});
