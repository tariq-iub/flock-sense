<?php

namespace App\Services;

use App\Models\Farm;
use App\Models\ProductionLog;
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
                foreach ($shed->flocks as $flock) {
                    $initialBirdCount = $flock->chicken_count;

                    // Get mortality data for this flock
                    $dailyMortality = $this->getMortality($flock, 1);  // Last 1 day
                    $weeklyMortality = $this->getMortality($flock, 7);  // Last 7 days
                    $allTimeMortality = $this->getMortality($flock, 'all');  // All-time mortality

                    // Add the mortalities to the totals
                    $totalDailyMortality += $dailyMortality;
                    $totalWeeklyMortality += $weeklyMortality;
                    $totalAllTimeMortality += $allTimeMortality;

                    // Calculate the live bird count for this flock
                    $liveBirdCount = $initialBirdCount - $allTimeMortality;

                    // Add the live bird count of this flock to the total for the farm
                    $totalLiveBirdCount += $liveBirdCount;

                    // Add live bird count and mortalities to the flock for reference
                    $flock->live_bird_count = $liveBirdCount;
                    $flock->daily_mortality = $dailyMortality;
                    $flock->weekly_mortality = $weeklyMortality;
                    $flock->all_time_mortality = $allTimeMortality;
                }
            }

            // Add total live bird count and total mortalities to the farm object
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
        $query = ProductionLog::where('flock_id', $flock->id);

        if ($period === 1) {
            // Last 1 day
            $query->where('production_log_date', '>=', now()->subDay());
        } elseif ($period === 7) {
            // Last 7 days
            $query->where('production_log_date', '>=', now()->subWeek());
        } elseif ($period === 'all') {
            // All-time mortality
            $query->where('production_log_date', '>=', $flock->start_date);
        }

        return $query->sum('day_mortality_count') + $query->sum('night_mortality_count');
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
                    $device->latest_sensor_data = !empty($data) ? (object)$data[0] : null;
                }
            }
        }

        return $farms;
    }
}
