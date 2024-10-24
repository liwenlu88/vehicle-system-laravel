<?php

namespace App\Http\Controllers;

use App\Http\Resources\Admin\ShowAdminResource;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $account = $request->input('account');
        $password = $request->input('password');

        $admin = Admin::where('account', $account)->first();

        if (!$admin || !Hash::check($password, $admin->password)) {
            return response()->json([
                'code' => 401,
                'message' => '账号或密码错误'
            ], 401);
        }

        $token = $admin->createToken(
            $admin->name,
            ['admin'],
            now()->addDays(7)
        )->plainTextToken;

        return response()->json([
            'code' => 0,
            'message' => '登录成功',
            'data' => new ShowAdminResource($admin),
            'token' => $token
        ]);
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
