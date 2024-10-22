<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserResourceCollection;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function options(): JsonResponse
    {
        return response()->json([
            'code' => 0,
            'message' => '选项信息',
            'data' => [
                'role_list' => Helper::getRoleList(),
                'position_status_list' => Helper::getPositionStatusList()
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $prePage = $request->input('prePage', 10);
        $page = $request->input('page', 1);

        $name = $request->input('name'); // 用户名
        $contact_tel = $request->input('contact_tel'); // 联系电话
        $roleId = $request->input('roleId'); // 角色ID
        $positionStatusId = $request->input('positionStatusId'); // 职位状态ID

        $userQuery = User::query();

        if (!empty($name)) {
            $userQuery->where('name', 'like', "%$name%");
        }

        if (!empty($contact_tel)) {
            $userQuery->where('contact_tel', 'like', "%$contact_tel%");
        }

        if (!empty($roleId)) {
            $userQuery->where('role_id', $roleId);
        }

        if (!empty($positionStatusId)) {
            $userQuery->where('position_status_id', $positionStatusId);
        }

        $users = $userQuery->paginate($prePage, ['*'], 'page', $page);

        return response()->json([
            'code' => 0,
            'message' => '用户信息列表',
            'data' => new UserResourceCollection($users)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        return Helper::authorizeAndRespond('create', User::class, function () {
            return response()->json([
                'code' => 0,
                'message' => '创建用户',
                'data' => [
                    'role_list' => Helper::getRoleList(),
                    'position_status_list' => Helper::getPositionStatusList()
                ]
            ]);
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Helper::authorizeAndRespond('create', User::class, function () use ($request) {
            // 验证用户信息
            $validatedData = Helper::requestValidation($request, StoreUserRequest::class);

            // 保存用户信息
            $user = new User();
            $user->fill($validatedData);
            $user->save();

            return response()->json([
                'code' => 0,
                'message' => '创建用户成功',
                'data' => [
                    'user' => new UserResource($user)
                ]
            ]);
        });
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Helper::authorizeAndRespond('show', User::class, function () use ($id) {
            $user = User::find($id);

            if (empty($user)) {
                return Helper::dataNotFound('用户不存在或已删除');
            }

            return response()->json([
                'code' => 0,
                'message' => '用户信息',
                'data' => [
                    'user' => new UserResource($user)
                ]
            ]);
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Helper::authorizeAndRespond('edit', User::class, function () use ($id) {
            $user = User::find($id);

            if (empty($user)) {
                return Helper::dataNotFound('用户不存在或已删除');
            }
            // 只有超级管理员自己可以编辑自己的信息
            if ($user->role_id == 1 && auth()->user()->id != $id) {
                return Helper::denyAccess();
            }

            return response()->json([
                'code' => 0,
                'message' => '编辑用户',
                'data' => [
                    'user' => new UserResource($user),
                    'role_list' => Helper::getRoleList(),
                    'position_status_list' => Helper::getPositionStatusList()
                ]
            ]);
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        return Helper::authorizeAndRespond('edit', User::class, function () use ($request) {
            $user = User::find($request->route('user'));

            if (empty($user)) {
                return Helper::dataNotFound('用户不存在或已删除');
            }

            // 记录原始数据
            Helper::setOriginalDataToRequestHeader($request, $user);

            $validatedData = Helper::requestValidation($request, UpdateUserRequest::class);

            // 只有超级管理员自己可以编辑自己的信息
            if ($user->role_id == 1 && auth()->user()->id != $user->id) {
                return Helper::denyAccess();
            }

            $user->fill($validatedData);
            $user->save();

            return response()->json([
                'code' => 0,
                'message' => '更新用户成功',
            ]);
        });
    }

    public function destroy(string $id)
    {
        return Helper::authorizeAndRespond('destroy', User::class, function () use ($id) {
            $user = User::find($id);

            if (empty($user)) {
                return Helper::dataNotFound('用户不存在或已删除');
            }

            // 超级管理员账户不能删除
            if ($user->role_id == 1) {
                return Helper::denyAccess();
            }

            $user->delete();

            return response()->json([
                'code' => 0,
                'message' => '删除用户成功',
            ]);
        });
    }
}
