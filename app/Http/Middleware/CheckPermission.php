<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 获取当前请求的 URL 并去掉前缀 api/
        $requestedUrl = substr($request->path(), 4);

        // 获取当前用户
        $user = $request->user();

        // 查找角色的权限
        $hasPermission = $user->roles->permissions->contains(function ($perm) use ($requestedUrl) {
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
