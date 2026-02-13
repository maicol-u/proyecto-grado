<?php

namespace Database\Seeders;

use App\Models\Sensor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sensor::create([
            'crop_id' => 1,
            'type_id' => 1,
            'name' => 'S001',
            'reading_interval' => 5,
            'min_value' => 60,
            'max_value' => 80,
        ]);
    }
}
