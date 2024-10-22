<?php

namespace App\Http\Middleware;

use App\Models\Log;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OperationLog
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // 处理请求
        $response = $next($request);

        // 获取当前请求的 URL 并去掉前缀 api/
        $path = substr($request->path(), 4);

        // 获取操作类型
        $method = $request->method();
        $operation = $this->getOperationType($method, $path);

        // 获取操作账户
        $account = $operation == 'Login' ? $request->input('account') : $user->account;

        $log = new Log();

        $log->account = $account;
        $log->path = $path;
        $log->method = $method;
        $log->operation = $operation;
        $log->ip_address = $request->ip();
        $log->user_agent = $request->userAgent();
        $log->request_data = json_encode($request->all());
        $log->response_data = $response->getContent();
        $log->status_code = $response->getStatusCode();

        // 如果是更新操作且响应状态码为 200，则记录原始数据和新数据
        if ($operation === 'Update' && $response->getStatusCode() === Response::HTTP_OK) {
            // 获取原始数据
            $originalData = json_decode($request->headers->get('X-Original-Data'), true);
            $log->original_data = json_encode($originalData);
            $log->new_data = json_encode($request->all());
        }

        $log->save();

        // 返回原始响应
        return $response;
    }

    /**
     * 根据请求方法确定操作类型
     */
    protected function getOperationType($method, $path): string
    {
        if ($path === 'auth/login' && $method === 'POST') {
            return 'Login';
        }

        if ($path === 'auth/logout' && $method === 'POST') {
            return 'Logout';
        }

        return match ($method) {
            'GET' => 'Select',
            'POST' => 'Create',
            'PUT', 'PATCH' => 'Update',
            'DELETE' => 'Destroy',
            default => 'Unknown',
        };
    }
}
