<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Driver::factory(20)->create();

        $driver = Driver::find(1);
        $driver->name = 'Driver';
        $driver->account = 'driver';
        $driver->position_status_id = 1;
        $driver->save();
    }
}
