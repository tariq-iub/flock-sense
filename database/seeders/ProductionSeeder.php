<?php

namespace Database\Seeders;

use App\Models\WeightLog;
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
        $timezone = 'Asia/Karachi';

        $log = \App\Models\ProductionLog::firstOrcreate([
            'shed_id' => 1,
            'flock_id' => 1,
            'production_log_date' => Carbon::createFromDate(2025, 05, 30, $timezone),
            'day_mortality_count' => 0,
            'night_mortality_count' => 0,
            'day_feed_consumed' => 0,
            'night_feed_consumed' => 0,
            'day_water_consumed' => 0,
            'night_water_consumed' => 0,
            // avg_feed_consumed is computed by database, don't include it
            'is_vaccinated' => false,
            'day_medicine' => '',
            'night_medicine' => '',
            'user_id' => 1,
        ]);

        WeightLog::firstOrCreate([
            'production_log_id' => $log->id,
            'flock_id' => $log->flock_id,
            'weighted_chickens_count' => 0,
            'total_weight' => 0,
        ]);
    }
}
