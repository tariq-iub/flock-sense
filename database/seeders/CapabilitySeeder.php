<?php

namespace Database\Seeders;

use App\Models\Capability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CapabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Temperature', 'icon' => 'bi bi-thermometer-half', 'unit' => '°C', 'description' => 'Ambient temperature inside the shed', 'is_active' => true],
            ['name' => 'Humidity', 'icon' => 'bi bi-droplet-half', 'unit' => '%', 'description' => 'Relative humidity level', 'is_active' => true],
            ['name' => 'NH3', 'icon' => 'bi bi-radioactive', 'unit' => 'ppm', 'description' => 'Ammonia concentration', 'is_active' => true],
            ['name' => 'CO2', 'icon' => 'bi bi-activity', 'unit' => 'ppm', 'description' => 'Carbon dioxide levels in the shed air.', 'is_active' => true],
            ['name' => 'Electricity', 'icon' => 'bi bi-lightning-charge', 'unit' => 'kWh', 'description' => 'Electrical energy consumption per hour.', 'is_active' => true]
        ];

        foreach ($data as $d) {
            Capability::firstOrCreate($d);
        }
    }
}
