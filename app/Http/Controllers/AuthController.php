<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AdminLoginRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Admin\ShowAdminResource;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('account', $request->input('account'))->first();

        $token = $user->createToken(
            'User Token',
            ['user'],
            now()->addDays(7)
        )->plainTextToken;

        return response()->json([
            'code' => 0,
            'message' => 'success',
            'data' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        dd($request->user());
        $request->user()->tokens()->delete();

        return response()->json([
            'code' => 0,
            'message' => 'success'
        ]);
    }

    public function adminLogin(AdminLoginRequest $request): JsonResponse
    {
        $admin = Admin::where('account', $request->input('account'))->first();

        $token = $admin->createToken(
            'Admin Token',
            ['admin'],
            now()->addDays(7)
        )->plainTextToken;

        return response()->json([
            'code' => 0,
            'message' => 'success',
            'data' => new ShowAdminResource($admin),
            'token' => $token
        ]);
    }

    public function adminLogout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'code' => 0,
            'message' => 'success'
        ]);
    }
}
