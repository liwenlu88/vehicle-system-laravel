<?php

namespace App\Helpers;

use App\Models\Menu;
use App\Models\Method;
use App\Models\PositionStatus;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class Helper
{
    /**
     * Policy 授权检查
     *
     * @param string $method
     * @return bool
     */
    public static function authorize(string $method): bool
    {
        // 获取当前用户的权限
        $permissions = Auth::user()->roles->permissions;

        // 检查是否有对应的权限
        return $permissions->contains(function ($perm) use ($method) {
            return $perm->methods()->contains('name', ucfirst($method));
        });
    }

    /**
     * @param string $action
     * @param string $modelClass
     * @param callable $successCallback
     * @return mixed
     *
     * Policy 授权权限检查并返回成功回调函数的响应
     */
    public static function authorizeAndRespond(string $action, string $modelClass, callable $successCallback): mixed
    {
        $response = Gate::inspect($action, $modelClass);

        if ($response->allowed()) { // 授权成功
            return $successCallback();
        } else { // 授权失败
            return self::denyAccess();
        }
    }

    /**
     * 禁止访问
     *
     * @return JsonResponse
     */
    public static function denyAccess(): JsonResponse
    {
        return response()->json([
            'code' => 403,
            'message' => '禁止访问',
        ], 403);
    }

    /**
     * 数据不存在
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function dataNotFound(string $message = '数据不存在'): JsonResponse
    {
        return response()->json([
            'code' => 404,
            'message' => $message,
        ], 404);
    }

    /**
     * 验证表单数据并返回验证后的数据
     *
     * @param Request $request
     * @param string $formRequestClass
     * @return array
     *
     */
    public static function requestValidation(Request $request, string $formRequestClass): array
    {
        // 使用服务容器解析 FormRequest 实例
        $formRequest = app($formRequestClass);
        // 将原始请求数据合并到 FormRequest 实例中
        $formRequest->merge($request->all());
        // 手动触发验证
        $formRequest->validateResolved();

        // 返回已验证的数据
        return $formRequest->validated();
    }

    /**
     * 将原数据添加到请求头 X-Original-Data -- 用于日志记录
     *
     * @param Request $request
     * @param Model $model
     * @return void
     *
     */
    public static function setOriginalDataToRequestHeader(Request $request, Model $model): void
    {
        // 获取原数据
        $originalData = $model->getAttributes();

        // 将原数据添加到请求头
        $request->headers->set('X-Original-Data', json_encode($originalData));
    }

    /**
     * 获取角色列表 -- 不包含超级管理员
     *
     * @return mixed
     *
     */
    public static function getRoleList(): mixed
    {
        return Role::whereNot('id', 1)->select('id', 'name')->get();
    }

    /**
     * 获取菜单列表
     *
     * @return mixed
     *
     */
    public static function getMenuList(): mixed
    {
        return Menu::select('id', 'name', 'url')->get();
    }

    /**
     * 获取请求方法列表
     *
     * @return mixed
     */
    public static function getMethodList(): mixed
    {
        return Method::select('id', 'name')->get();
    }

    /**
     * 获取职位状态列表
     *
     * @return mixed
     *
     */
    public static function getPositionStatusList(): mixed
    {
        return PositionStatus::select('id', 'name')->get();
    }
}
