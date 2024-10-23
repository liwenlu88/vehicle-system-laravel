<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * 模块访问权限中间件
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 获取当前请求的 URL 并去掉前缀 api/
        $requestedUrl = substr($request->path(), 4);

        // 获取当前用户及其角色权限缓存键
        $user = Auth::user();
        $cacheKey = 'roles_permissions_' . $user->role_id;

        // 从 Redis 获取权限
        $permissions = Redis::get($cacheKey);

        // 如果 Redis 缓存中没有权限数据，从数据库获取并缓存
        if (empty($permissions)) {
            $permissions = $user->roles->permissions()->with('menus')->get()->toJson();
            Redis::setex($cacheKey, 3600, $permissions);
        }

        // 解码 JSON 格式的权限数据
        $permissions = json_decode($permissions);

        // 检查当前 URL 是否匹配用户的权限菜单 (允许子菜单访问 如 /users/create)
        $hasPermission = collect($permissions)->contains(function ($perm) use ($requestedUrl) {
            return $perm->menus->url === $requestedUrl || str_starts_with($requestedUrl, $perm->menus->url . '/');
        });

        if (!$hasPermission) {
            return response()->json([
                'code' => 403,
                'message' => '禁止访问'
            ], 403);
        }

        return $next($request);
    }
}
