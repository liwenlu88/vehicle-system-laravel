<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\ShowUserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'account' => 'required',
            'password' => 'required',
        ], [
            'account.required' => '请输入账号',
            'password.required' => '请输入密码'
        ]);

        $credentials = $request->only('account', 'password');

        if (Auth::attempt($credentials)) {
            $token = $request->user()->createToken(
                Auth::user()->name,
                ['users'],
                now()->addDays(7)
            )->plainTextToken;

            return response()->json([
                'code' => 0,
                'message' => '登录成功',
                'data' => new ShowUserResource($request->user()),
                'token' => $token
            ]);
        }

        return response()->json([
            'code' => 401,
            'message' => '账号或密码错误'
        ], 401);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'code' => 0,
            'message' => '用户已退出'
        ]);
    }
}
