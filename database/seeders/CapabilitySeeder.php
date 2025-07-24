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
            ['name' => 'Temperature', 'icon' => 'bi bi-thermometer-half', 'is_active' => true],
            ['name' => 'Humidity', 'icon' => 'bi bi-droplet-half', 'is_active' => true],
            ['name' => 'NH3', 'icon' => 'bi bi-radioactive', 'is_active' => true],
            ['name' => 'CO2', 'icon' => 'bi bi-activity', 'is_active' => true],
            ['name' => 'Electricity', 'icon' => 'bi bi-lightning-charge', 'is_active' => true]
        ];

        foreach ($data as $d) {
            Capability::firstOrCreate($d);
        }
    }
}
