<?php

namespace Database\Seeders;

use App\Models\SensorType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SensorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SensorType::create([
            'name' => 'Sensor humedad de suelo',
            'unit' => 'Porcentaje',
            'symbol' => '%'
        ]);
    }
}
