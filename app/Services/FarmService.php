<?php

namespace App\Services;

use App\Models\Farm;
use App\Models\ProductionLog;
use App\Models\WeightLog;
use App\Services\DynamoDbService;
use Carbon\Carbon;

class FarmService
{
    protected $dynamo;

    public function __construct(DynamoDbService $dynamoDbService)
    {
        $this->dynamo = $dynamoDbService;
    }

    /**
     * Fetch and process farm data, including live bird count and mortalities.
     */
    public function processFarmData($farms)
    {
        foreach ($farms as $farm) {
            $totalLiveBirdCount = 0;
            $totalDailyMortality = 0;
            $totalWeeklyMortality = 0;
            $totalAllTimeMortality = 0;

            foreach ($farm->sheds as $shed) {
                // ✅ Only take the latest flock in this shed
                $flock = $shed->flocks->sortByDesc('id')->first();

                if ($flock) {
                    $initialBirdCount = $flock->chicken_count;

                    // Get mortality data for this flock
                    $dailyMortality = $this->getMortality($flock, 1);
                    $weeklyMortality = $this->getMortality($flock, 7);
                    $allTimeMortality = $this->getMortality($flock, 'all');

                    // Add the mortalities to the totals
                    $totalDailyMortality += $dailyMortality;
                    $totalWeeklyMortality += $weeklyMortality;
                    $totalAllTimeMortality += $allTimeMortality;

                    // Calculate the live bird count for this flock
                    $liveBirdCount = $initialBirdCount - $allTimeMortality;

                    // Add to farm totals
                    $totalLiveBirdCount += $liveBirdCount;

                    // Attach values to flock
                    $flock->live_bird_count = $liveBirdCount;
                    $flock->daily_mortality = $dailyMortality;
                    $flock->weekly_mortality = $weeklyMortality;
                    $flock->all_time_mortality = $allTimeMortality;
                }
            }

            // Attach totals to farm
            $farm->total_live_bird_count = $totalLiveBirdCount;
            $farm->total_daily_mortality = $totalDailyMortality;
            $farm->total_weekly_mortality = $totalWeeklyMortality;
            $farm->total_all_time_mortality = $totalAllTimeMortality;
        }

        return $farms;
    }

    /**
     * Get the mortality count for a flock based on the period.
     */
    public function getMortality($flock, $period)
    {
        $query = ProductionLog::where('flock_id', $flock->id)
            ->orderByDesc('production_log_date'); // latest first

        if ($period === 1) {
            // Last 1 record (latest entry)
            $logs = $query->limit(1)->get();
        } elseif ($period === 7) {
            // Last 7 records
            $logs = $query->limit(7)->get();
        } elseif ($period === 'all') {
            // All records since flock started
            $logs = $query->where('production_log_date', '>=', $flock->start_date)->get();
        } else {
            return 0; // fallback
        }

        return $logs->sum('day_mortality_count') + $logs->sum('night_mortality_count');
    }

    /**
     * Fetch sensor data for devices in the farm.
     */
    public function fetchSensorData($farms)
    {
        foreach ($farms as $farm) {
            foreach ($farm->sheds as $shed) {
                foreach ($shed->devices as $device) {
                    $data = $this->dynamo->getSensorData([$device->id], null, null, true); // correct argument order
//                    $data = $this->dynamo->getLatestSensorData([$device->id]); // correct argument order
                    $device->latest_sensor_data = $data[$device->id] ?? null;
                }
            }
        }

        return $farms;
    }

    /**
     * Attach the latest weight log to each flock in the given farms.
     */
    public function attachLatestWeightLogs($farms)
    {
        foreach ($farms as $farm) {
            foreach ($farm->sheds as $shed) {
                // ✅ Only latest flock
                $flock = $shed->flocks->sortByDesc('id')->first();

                if ($flock) {
                    $latestWeightLog = WeightLog::where('flock_id', $flock->id)
                        ->orderByDesc('created_at')
                        ->select([
                            'id',
                            'avg_weight',
                            'avg_weight_gain',
                            'feed_conversion_ratio',
                            'created_at'
                        ])
                        ->first();

                    $flock->latest_weight_log = $latestWeightLog
                        ? [
                            'avg_weight' => $latestWeightLog->avg_weight,
                            'avg_weight_gain' => $latestWeightLog->avg_weight_gain,
                            'feed_conversion_ratio' => $latestWeightLog->feed_conversion_ratio,
                            'created_at' => $latestWeightLog->created_at,
                        ]
                        : null;
                }
            }
        }

        return $farms;
    }
}
