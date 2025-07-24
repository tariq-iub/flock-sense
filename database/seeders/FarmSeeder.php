<?php

namespace Database\Seeders;

use App\Models\Farm;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

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
            'latitude' => 31.90610000,
            'longitude' => 74.23520000,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
