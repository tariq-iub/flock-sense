<?php

namespace Database\Seeders;

use App\Models\Farm;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

        DB::table('farm_managers')->insert([
            'farm_id' => 1,
            'manager_id' => 2,
            'link_date' => Carbon::now(),
        ]);
    }
}
