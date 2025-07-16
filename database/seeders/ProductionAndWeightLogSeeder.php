<?php

namespace Database\Seeders;

use App\Models\ProductionLog;
use App\Models\WeightLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionAndWeightLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 30 production logs
        ProductionLog::factory()
            ->count(30)
            ->create()
            ->each(function($productionLog) {
                // For each ProductionLog, randomly decide to create a WeightLog
                if (rand(0, 1)) {
                    // Use factory for sample values, but override production_log_id and flock_id
                    $weightLog = WeightLog::factory()->make();
                    $weightLog->production_log_id = $productionLog->id;
                    $weightLog->flock_id = $productionLog->flock_id;
                    $weightLog->save();
                }
            });
    }
}
