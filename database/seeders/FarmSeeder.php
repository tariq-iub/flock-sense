<?php

namespace Database\Seeders;

use App\Models\Farm;
use Illuminate\Database\Seeder;

class FarmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Farm::firstOrCreate([
            'name' => 'Sadoki Farm',
            'address' => 'Sadoki',
            'owner_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
