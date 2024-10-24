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
        User::factory(20)->create();

        $driver = User::find(1);
        $driver->name = 'User';
        $driver->account = 'user';
        $driver->position_status_id = 1;
        $driver->save();
    }
}
