<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\OperationLog;
use Illuminate\Support\Facades\Route;

/** 客户端 */

Route::group([
    'middleware' => [
        'auth:user',
        OperationLog::class, // 操作日志
    ]
], function () {
    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['auth:user']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});


/** 管理端 */

Route::group([
    'prefix' => 'admin',
    'middleware' => [
        'auth:admin',
        OperationLog::class, // 操作日志
        CheckPermission::class // 权限检查
    ]
], function () {
    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('login', [AuthController::class, 'adminLogin'])->withoutMiddleware(['auth:admin', CheckPermission::class]);
        Route::post('logout', [AuthController::class, 'adminLogout'])->withoutMiddleware([CheckPermission::class]);
    });

    Route::middleware([])->group(function () {
        // Admin 用户管理
        Route::get('users/options', [AdminController::class, 'options']);
        Route::resource('users', AdminController::class);

        // 角色管理
        Route::resource('roles', RoleController::class);
    });
});
