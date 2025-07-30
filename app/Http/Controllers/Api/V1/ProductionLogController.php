<?php

namespace App\Http\Controllers\Api\V1;

use App\Exports\ProductionLogsExport;
use App\Models\Chart;
use App\Models\Farm;
use App\Models\Flock;
use App\Models\ProductionLog;
use App\Models\Shed;
use App\Models\WeightLog;
use App\Services\WeightLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Controllers\ApiController;

class ProductionLogController extends ApiController
{
    public function index(Request $request)
    {
        $logs = QueryBuilder::for(ProductionLog::class)
            ->with(['shed', 'flock', 'user', 'weightLog'])
            ->allowedFilters([
                AllowedFilter::exact('shed_id'),
                AllowedFilter::exact('flock_id'),
            ])
            ->latest()
            ->get();

        return response()->json($logs);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shed_id'                => 'required|exists:sheds,id',
            'flock_id'               => 'required|exists:flocks,id',
            'day_mortality_count'    => 'required|integer|min:0',
            'night_mortality_count'  => 'required|integer|min:0',
            'net_count'              => 'nullable|integer|min:0',
            'day_feed_consumed'      => 'required|numeric|min:0',
            'night_feed_consumed'    => 'required|numeric|min:0',
            'day_water_consumed'     => 'required|numeric|min:0',
            'night_water_consumed'   => 'required|numeric|min:0',
            'is_vaccinated'          => 'required|boolean',
            'day_medicine'           => 'nullable|string',
            'night_medicine'         => 'nullable|string',
            'with_weight_log'        => 'nullable|boolean',
            'weighted_chickens_count'=> 'nullable|numeric|min:0',
            'total_weight'           => 'nullable|numeric|min:0',
        ]);

        $flock = Flock::findOrFail($validated['flock_id']);

        // Find the last production log to calculate net_count correctly
        $lastLog = ProductionLog::where('flock_id', $flock->id)->latest()->first();
        $lastNetCount = $lastLog ? $lastLog->net_count : $flock->chicken_count;

        // Calculate net_count for the new log
        $net_count = $lastNetCount - ($validated['day_mortality_count'] + $validated['night_mortality_count']);

        // Calculate age (days since flock's start_date)
        $age = $flock->start_date->diffInDays(today());

        // Calculate livability
        $livability = $flock->chicken_count > 0
            ? round(($net_count / $flock->chicken_count) * 100, 3)
            : 0;

        // Prepare data for new ProductionLog
        $productionLog = ProductionLog::create([
            'shed_id'               => $validated['shed_id'],
            'flock_id'              => $validated['flock_id'],
            'age'                   => $age,
            'day_mortality_count'   => $validated['day_mortality_count'],
            'night_mortality_count' => $validated['night_mortality_count'],
            'net_count'             => $net_count,
            'livability'            => $livability,
            'day_feed_consumed'     => $validated['day_feed_consumed'],
            'night_feed_consumed'   => $validated['night_feed_consumed'],
            'day_water_consumed'    => $validated['day_water_consumed'],
            'night_water_consumed'  => $validated['night_water_consumed'],
            'is_vaccinated'         => $validated['is_vaccinated'],
            'day_medicine'          => $validated['day_medicine'],
            'night_medicine'        => $validated['night_medicine'],
            'user_id'               => Auth::id(),
        ]);

        // Optionally: Only create weight log if provided and valid
        if (
            !empty($validated['with_weight_log']) &&
            !empty($validated['weighted_chickens_count']) &&
            !empty($validated['total_weight'])
        ) {
            app(WeightLogService::class)->createOrUpdateWeightLog(
                $productionLog,
                $validated['weighted_chickens_count'],
                $validated['total_weight']
            );
        }

        return response()->json($productionLog, 201);
    }

    public function show(ProductionLog $productionLog)
    {
        return response()->json($productionLog->load(['shed', 'flock', 'user']));
    }

    public function update(Request $request, ProductionLog $productionLog)
    {
        $validatedData = $request->validate([
            'chicken_count' => 'integer|min:0',
            'age' => 'integer|min:0',
            'mortality_count' => 'integer|min:0',
            'total_weight' => 'numeric|min:0',
            'water_consumed' => 'numeric|min:0',
            'feed_consumed' => 'numeric|min:0',
            'day_lowest_temperature' => 'numeric|nullable',
            'day_lowest_temperature_time' => 'date|nullable',
            'day_peak_temperature' => 'numeric|nullable',
            'day_peak_temperature_time' => 'date|nullable',
            'day_lowest_humidity' => 'numeric|nullable',
            'day_lowest_humidity_time' => 'date|nullable',
            'day_peak_humidity' => 'numeric|nullable',
            'day_peak_humidity_time' => 'date|nullable',
            'fcr' => 'numeric|min:0',
            'fcr_standard_diff' => 'numeric',
            'vet_visited' => 'boolean',
            'is_vaccinated' => 'boolean'
        ]);

        $productionLog->update($validatedData);

        return response()->json($productionLog);
    }

    public function destroy(ProductionLog $productionLog)
    {
        $productionLog->delete();
        return response()->json(null, 204);
    }

    public function dailyReportHeaders($shedId)
    {
        if($shedId) {
            return response()
                ->json(Shed::with('latestFlockProductionLogs')->find($shedId));
        }
        else {
            return response()->json([]);
        }
    }
}
