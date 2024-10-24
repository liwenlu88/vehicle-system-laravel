<?php

namespace Database\Seeders;

use App\Models\Admin;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MenuSeeder::class, // 菜单
            MethodSeeder::class, // 方法
            PermissionSeeder::class, // 权限
            RoleSeeder::class, // 角色
            PositionStatusSeeder::class, // 职位状态
            AdminSeeder::class, // 管理员
            UserSeeder::class, // 用户
        ]);
    }
}
