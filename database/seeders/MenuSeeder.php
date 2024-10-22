<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Dashboard',
                'url' => 'dashboard',
            ],
            [
                'name' => '用户管理',
                'url' => 'users',
            ],
            [
                'name' => '角色管理',
                'url' => 'roles',
            ],
            [
                'name' => '权限管理',
                'url' => 'permissions',
            ],
            [
                'name' => '菜单管理',
                'url' => 'menus',
            ],
        ];

        foreach ($menus as $menu) {
            \App\Models\Menu::create($menu);
        }
    }
}
