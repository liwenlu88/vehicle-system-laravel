<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::factory(2000)->create();

        $user = Admin::first();
        $user->name = 'Super Admin';
        $user->account = 'admin';
        $user->role_id = 1;
        $user->position_status_id = 1;
        $user->save();
    }
}
