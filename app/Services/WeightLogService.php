<?php

namespace App\Services;

use App\Models\Chart;
use App\Models\ChartData;
use App\Models\ProductionLog;
use App\Models\WeightLog;

class WeightLogService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Calculate and create (or update) a WeightLog.
     *
     * @param ProductionLog $log
     * @param int $weighted_chickens_count
     * @param double $total_weight
     * @param array $options (optionally pass additional info, e.g. custom date, etc)
     * @return WeightLog
     */
    public function createOrUpdateWeightLog(
        ProductionLog $log,
        int $weighted_chickens_count,
        float $total_weight,
        array $options = []
    ): WeightLog {
        $flock = $log->flock;
        if (! $flock) {
            throw new \Exception('No flock found for this ProductionLog');
        }

        $lastWeightLog = WeightLog::where('flock_id', $flock->id)
            ->orderByDesc('production_log_id')->first();

        $lastProductionLog = $lastWeightLog
            ? ProductionLog::find($lastWeightLog->production_log_id)
            : null;

        $age = $log->age;
        $net_count = $log->net_count;
        $livability = $log->livability;

        $avg_weight = $weighted_chickens_count > 0
            ? round($total_weight / $weighted_chickens_count, 3)
            : 0.0;

        $avg_weight_gain = ($lastWeightLog)
            ? daily_weight_gain($avg_weight, $lastWeightLog->avg_weight)
            : 0.0;

        $flock_weight = flock_weight($avg_weight, $net_count);
        $flock_weight_gain = flock_daily_weight_gain($avg_weight_gain, $net_count);

        $total_feed = $log->todate_feed_consumed - ($lastProductionLog ? $lastProductionLog->todate_feed_consumed : 0);

        $feed_efficiency = ($total_feed > 0)
            ? feed_efficiency($flock_weight_gain, $total_feed)
            : 0.0;

        $feed_conversion_ratio = $flock_weight_gain > 0
            ? feed_conversion_ratio($total_feed, $flock_weight_gain)
            : 0;

        // Chart for benchmark (optional)
        $chart = ChartData::where(['type' => 'General', 'day' => $log->age])->first();
        $expected_weight = $chart ? $chart->weight : $avg_weight;
        $adjusted_fcr = adjusted_fcr($feed_conversion_ratio, $expected_weight, $avg_weight);
        $fcr_standard_diff = ($chart && $chart->fcr)
            ? round($chart->fcr - $feed_conversion_ratio, 3)
            : 0;

        $previousLogs = ProductionLog::with('weightLog')
            ->where('flock_id', $flock->id)
            ->whereDate('production_log_date', '<=', $log->production_log_date->toDateString())
            ->get();

        $avgWeightCollection = collect($previousLogs->flatMap(function ($log) {
            return $log->weightLog ? [$log->weightLog->avg_weight] : [];
        }));

        $standard_deviation = flock_standard_deviation($avgWeightCollection->toArray());

        $coefficient_of_variation = flock_cv($avgWeightCollection->toArray());
        $uniformity = flock_uniformity($avgWeightCollection->toArray());

        $production_efficiency_factor = production_efficiency_factor(
            $livability,
            $avg_weight / 1000,
            $age,
            $feed_conversion_ratio
        );

        // Create or Update WeightLog
        return WeightLog::updateOrCreate([
            'production_log_id' => $log->id,
            'flock_id' => $flock->id,
        ], [
            'weighted_chickens_count' => $weighted_chickens_count,
            'total_weight' => $total_weight,
            'avg_weight' => $avg_weight,
            'avg_weight_gain' => $avg_weight_gain,
            'aggregated_total_weight' => $flock_weight,
            'flock_weight_gain' => $flock_weight_gain,
            'feed_efficiency' => $feed_efficiency,
            'feed_conversion_ratio' => $feed_conversion_ratio,
            'adjusted_feed_conversion_ratio' => $adjusted_fcr,
            'fcr_standard_diff' => $fcr_standard_diff,
            'standard_deviation' => $standard_deviation,
            'coefficient_of_variation' => $coefficient_of_variation,
            'uniformity' => $uniformity,
            'production_efficiency_factor' => $production_efficiency_factor,
        ]);
    }
}
