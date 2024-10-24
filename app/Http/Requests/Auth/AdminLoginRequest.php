<?php

namespace App\Http\Requests\Auth;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class AdminLoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account' => [
                'required',
                'exists:admins,account'
            ],
            'password' => [
                'required',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'account.required' => '账号不可为空，请填写账号',
            'account.exists' => '账号不存在，请联系管理员',
            'password.required' => '密码不可为空，请填写密码',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $account = $this->input('account');
            $password = $this->input('password');

            // 查询管理员信息
            $admin = Admin::where('account', $account)->first();

            // 验证密码是否正确
            if ($admin && !Hash::check($password, $admin->password)) {
                $validator->errors()->add('password', '密码输入错误');
            }
        });
    }
}
