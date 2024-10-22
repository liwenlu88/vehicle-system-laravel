<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'between:2,10',
                'unique:users,name',
            ],
            'contact_tel' => [
                'required',
                'regex:/^1[3-9]\d{9}$/',
                'unique:users,contact_tel',
            ],
            'account' => [
                'required',
                'between:5,18',
                'regex:/^[A-Za-z0-9@_-]+$/',
                'unique:users,account',
            ],
            'password' => [
                'required',
                'between:5,18',
                'confirmed',
            ],
            'role_id' => [
                'required',
                'exists:roles,id',
            ],
            'position_status_id' => [
                'required',
                'exists:position_statuses,id',
            ],
            'description' => [
                'nullable',
                'max:255',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '用户名不能为空',
            'name.between' => '用户名长度必须在2-10之间',
            'name.unique' => '用户名已存在',
            'contact_tel.required' => '手机号不能为空',
            'contact_tel.regex' => '手机号格式不正确',
            'contact_tel.unique' => '手机号已存在',
            'account.required' => '账号不能为空',
            'account.between' => '账号长度必须在5-18之间',
            'account.regex' => '账号只能包含字母、数字、@、_、-',
            'account.unique' => '账号已存在',
            'password.required' => '密码不能为空',
            'password.between' => '密码长度必须在5-18之间',
        ];
    }
}
