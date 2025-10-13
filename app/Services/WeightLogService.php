<?php

namespace App\Services;

use App\Models\WeightLog;
use App\Models\ProductionLog;
use App\Models\Chart;
use App\Models\ChartData;
use Illuminate\Support\Carbon;

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
        int           $weighted_chickens_count,
        float         $total_weight,
        array         $options = []
    ): WeightLog
    {
        $flock = $log->flock;

        if (!$flock) {
            throw new \Exception('No flock found for this ProductionLog');
        }

        $total_weight = $total_weight * 1000;

        // 1. Calculate avg_weight
        $avg_weight = $weighted_chickens_count > 0
            ? round($total_weight / $weighted_chickens_count, 3)
            : 0;

        // 2. Previous WeightLog for gain calculation (previous for same flock and earlier date)
        $production_log_date = $log->production_log_date ?? Carbon::now();
        $previousWeightLog = WeightLog::whereHas('productionLog', function ($q) use ($flock, $production_log_date) {
            $q->where('flock_id', $flock->id)
                ->whereDate('production_log_date', '<=', $production_log_date->toDateString());
        })
            ->orderByDesc('id')->first();

        $avg_weight_gain = ($previousWeightLog)
            ? round($avg_weight - $previousWeightLog->avg_weight, 3)
            : 0;

        // 3. Aggregated total weight
        $aggregated_total_weight = round($avg_weight * $log->net_count, 3);

        // 4. Feed efficiency & FCR
        $total_feed = $log->day_feed_consumed + $log->night_feed_consumed;
        $feed_efficiency = ($total_feed > 0)
            ? round($aggregated_total_weight / $total_feed, 3)
            : 0;

        $feed_conversion_ratio = $aggregated_total_weight > 0
            ? round($total_feed / $aggregated_total_weight, 3)
            : 0;

        // 5. Chart for benchmark (optional)
        $chart = ChartData::where(['type' => 'General', 'day' => $log->age])->first();
        $expected_weight = $chart ? $chart->weight : $avg_weight;
        $adjusted_fcr = round($feed_conversion_ratio + (($expected_weight - $avg_weight) / 4500), 3);
        $fcr_standard_diff = ($chart && $chart->fcr)
            ? round($chart->fcr - $feed_conversion_ratio, 3)
            : 0;

        // 6. Standard deviation and coefficient of variation
        $previousLogs = ProductionLog::with('weightLog')
            ->where('flock_id', $flock->id)
            ->whereDate('production_log_date', '<=', $production_log_date->toDateString())
            ->get();

        $avgWeightCollection = collect($previousLogs->flatMap(function ($log) {
            return $log->weightLog ? [$log->weightLog->avg_weight] : [];
        }));

        $count = $avgWeightCollection->count();
        if ($count > 0) {
            $mean = $avgWeightCollection->avg();
            $variance = $avgWeightCollection->reduce(function ($carry, $value) use ($mean) {
                    return $carry + pow($value - $mean, 2);
                }, 0) / $count;
            $standard_deviation = round(sqrt($variance), 3);
        } else {
            $standard_deviation = 0;
        }

        $coefficient_of_variation = ($avg_weight > 0)
            ? round(($standard_deviation / $avg_weight) * 100, 3)
            : 0;

        // 7. Production Efficiency Factor
        $livability = $log->livability;
        $production_efficiency_factor = ($log->age > 0 && $feed_conversion_ratio > 0)
            ? round($livability * ($aggregated_total_weight / 1000) / ($log->age * $feed_conversion_ratio), 3)
            : 0;

        // 8. Create or Update WeightLog
        return WeightLog::updateOrCreate([
            'production_log_id' => $log->id,
            'flock_id' => $flock->id,
        ], [
            'weighted_chickens_count' => $weighted_chickens_count,
            'total_weight' => $total_weight,
            'avg_weight' => $avg_weight,
            'avg_weight_gain' => $avg_weight_gain,
            'aggregated_total_weight' => $aggregated_total_weight,
            'feed_efficiency' => $feed_efficiency,
            'feed_conversion_ratio' => $feed_conversion_ratio,
            'adjusted_feed_conversion_ratio' => $adjusted_fcr,
            'fcr_standard_diff' => $fcr_standard_diff,
            'standard_deviation' => $standard_deviation,
            'coefficient_of_variation' => $coefficient_of_variation,
            'production_efficiency_factor' => $production_efficiency_factor,
        ]);
    }
}
