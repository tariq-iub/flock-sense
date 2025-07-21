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
        $data = ['temperature', 'humidity', 'nh3', 'co2', 'electricity'];
        foreach ($data as $d) {
            Capability::firstOrCreate([
                'name' => $d,
                'is_active' => true,
            ]);
        }
    }
}
