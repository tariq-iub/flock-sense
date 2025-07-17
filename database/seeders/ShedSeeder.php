<?php

namespace Database\Seeders;

use App\Models\Shed;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shed::firstOrCreate([
            'farm_id' => 1,
            'name' => 'Shed A',
            'capacity' => 30000,
            'type' => 'broiler',
            'description' => 'Test Shed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
