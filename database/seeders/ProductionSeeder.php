<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductionLog::firstOrcreate([
            'shed_id' => 1,
            'flock_id' => 1,
            'production_log_date' => Carbon::createFromDate(2025, 05, 30, $timezone),
            'day_mortality_count' => 0,
            'night_mortality_count' => 0,

        ]);
    }
}
