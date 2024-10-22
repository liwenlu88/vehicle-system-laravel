<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(2000)->create();

        $user = User::first();
        $user->name = 'Super Admin';
        $user->account = 'admin';
        $user->role_id = 1;
        $user->position_status_id = 1;
        $user->save();
    }
}
