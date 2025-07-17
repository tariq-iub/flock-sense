<?php

namespace Database\Seeders;

use App\Models\Flock;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timezone = 'Asia/Karachi';
        Flock::firstOrCreate([
            'name' => 'Flock 101',
            'shed_id' => 1,
            'breed_id' => 1,
            'chicken_count' => 27000,
            'start_date' => Carbon::createFromDate(2025, 05, 30, $timezone),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
