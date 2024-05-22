<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sensor_Data;

class SensorDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Sensor_Data::create([
            'oxygen_rate' => 88,
            'heart_rate' => 111,
            'clieus' => 38,
        ]);

    }
}
