<?php

namespace App\Http\Controllers\Api\V1;

use App\Exports\ProductionLogsExport;
use App\Http\Resources\DailyReportHeaders;
use App\Models\Chart;
use App\Models\Farm;
use App\Models\Flock;
use App\Models\ProductionLog;
use App\Models\Shed;
use App\Models\WeightLog;
use App\Services\WeightLogService;
use http\Env\Response;
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
            $shed = Shed::with('latestFlock.productionLogs')->find($shedId);
            if (!$shed) {
                return response()->json(['message' => 'Shed not found'], 404);
            }

            $productionLogDates = $shed->latestFlock->productionLogs->pluck('production_log_date');

            return response()->json([
                'shed_id' => $shed->id,
                'shed_name' => $shed->name,
                'flock_id' => $shed->latestFlock->id,
                'flock_name' => $shed->latestFlock->name,
                'production_log_dates' => $productionLogDates,
            ], 200);
        }
        else {
            return response()->json(['message' => 'Shed id is not provided.'], 404);
        }
    }

    public function dailyReport(Request $request, $version = 'en')
    {
        $request->validate([
            'shed_id' => 'required|integer|exists:sheds,id',
            'date'    => 'required|date_format:Y-m-d', // Ensure date format
        ]);

        $shedId = $request->input('shed_id');
        $reportDate = $request->input('date');

        $shed = Shed::with(['latestFlock'])->find($shedId);
        if (!$shed) {
            return response()->json(['message' => 'Shed not found.'], 404);
        }

        $latestFlock = $shed->latestFlock;
        if (!$latestFlock) {
            return response()->json(['message' => 'No active flock found for this shed.'], 404);
        }

        $logs = ProductionLog::where('flock_id', $latestFlock->id)
            ->where('shed_id', $shedId)
            ->whereDate('production_log_date', $reportDate)
            ->with(['user', 'weightLog'])
            ->get();

        if ($logs->isEmpty()) {
            return response()->json(['message' => 'No production data found for the specified date.'], 404);
        }

        $data = $logs->first();
        if($version == 'en') {
            return response()->json([
                'shed' => $shed->name,
                'flock' => $latestFlock->name,
                'flock_count' => $latestFlock->chicken_count,
                'date' => $data->production_log_date->format('d-m-Y'),
                "age" => $data->age . ' Days',
                "day_mortality_count" => $data->day_mortality_count,
                "night_mortality_count" => $data->night_mortality_count,
                "net_count" => $data->net_count,
                "livability" => $data->livability . ' %',
                "day_feed_consumed" => round($data->day_feed_consumed / 1000, 2) . ' Kg',
                "night_feed_consumed" => round($data->night_feed_consumed / 1000, 2) . ' Kg',
                "avg_feed_consumed" => round($data->avg_feed_consumed / 1000, 2) . ' Kg',
                "day_water_consumed" => round($data->day_water_consumed / 1000, 2) . ' L',
                "night_water_consumed" => round($data->night_water_consumed / 1000, 2) . ' L',
                "avg_water_consumed" => round($data->avg_water_consumed / 1000, 2) . ' L',
                "is_vaccinated" => ($data->is_vaccinated ? 'Yes' : 'No'),
                "day_medicine" => $data->day_medicine ?? '',
                "night_medicine" => $data->night_medicine ?? '',
                'weighted_chickens' => $data->weightLog->weighted_chickens_count ?? '',
                'recorded_weight' => ($data->weightLog) ? round($data->weightLog->total_weight / 1000, 2) . ' Kg' : '',
                'avg_weight' => ($data->weightLog) ? round($data->weightLog->avg_weight / 1000, 2) . ' Kg' : '',
                'avg_weight_gain' => ($data->weightLog) ? round($data->weightLog->avg_weight_gain / 1000, 2) . ' Kg' : '',
                'flock_weight' => ($data->weightLog) ? round($data->weightLog->aggregated_total_weight / 1000, 2) . ' Kg' : '',
                'feed_efficiency' => ($data->weightLog) ? round($data->weightLog->feed_efficiency, 2) : '',
                'fcr' => ($data->weightLog) ? round($data->weightLog->feed_conversion_ratio, 2) : '',
                'adjusted_fcr' => ($data->weightLog) ? round($data->weightLog->adjusted_feed_conversion_ratio, 2) : '',
                'fcr_diff' => ($data->weightLog) ? round($data->weightLog->fcr_standard_diff, 2) : '',
                'cv' => ($data->weightLog) ? round($data->weightLog->coefficient_of_variation, 2) . ' %' : '',
                'pef' => ($data->weightLog) ? round($data->weightLog->production_efficiency_factor, 2) : '',
                'submit_by' => $data->user->name,
                'submit_at' => $data->created_at->diffForHumans(),
            ], 200);
        }
        else {
            return response()->json([
                'شیڈ' => $shed->name, // Shed
                'فلاک' => $latestFlock->name, // Flock
                'فلاک تعداد' => $latestFlock->chicken_count . '', // Flock Count
                'تاریخ' => $data->production_log_date->format('d-m-Y'), // Date
                'عمر' => $data->age . ' دن', // Age (Days)
                'دن اموات تعداد' => $data->day_mortality_count . '', // Day Mortality Count
                'شب اموات تعداد' => $data->night_mortality_count . '', // Night Mortality Count
                'خالص تعداد' => $data->net_count . '', // Net Count
                'زندہ رہنے کی شرح' => $data->livability . '', // Livability
                'دن خوراک صرف شدہ' => round($data->day_feed_consumed / 1000, 2) . ' کلوگرام', // Day Feed Consumed (Kg)
                'شب خوراک صرف شدہ' => round($data->night_feed_consumed / 1000, 2) . ' کلوگرام', // Night Feed Consumed (Kg)
                'اوسط خوراک صرف شدہ' => round($data->avg_feed_consumed / 1000, 2) . ' کلوگرام', // Avg Feed Consumed (Kg)
                'دن پانی صرف شدہ' => round($data->day_water_consumed / 1000, 2) . ' لیٹر', // Day Water Consumed (L)
                'شب پانی صرف شدہ' => round($data->night_water_consumed / 1000, 2) . ' لیٹر', // Night Water Consumed (L)
                'اوسط پانی صرف شدہ' => round($data->avg_water_consumed / 1000, 2) . ' لیٹر', // Avg Water Consumed (L)
                'ویکسین شدہ' => ($data->is_vaccinated ? 'ہاں' : 'نہیں'), // Is Vaccinated (Yes/No)
                'دن دوا' => $data->day_medicine ?? '', // Day Medicine
                'شب دوا' => $data->night_medicine ?? '', // Night Medicine
                'وزن شدہ چکنز' => $data->weightLog->weighted_chickens_count . '' ?? '', // Weighted Chickens
                'ریکارڈ شدہ وزن' => ($data->weightLog) ? round($data->weightLog->total_weight / 1000, 2) . ' کلوگرام' : '', // Recorded Weight (Kg)
                'اوسط وزن' => ($data->weightLog) ? round($data->weightLog->avg_weight / 1000, 2) . ' کلوگرام' : '', // Avg Weight (Kg)
                'اوسط وزن میں اضافہ' => ($data->weightLog) ? round($data->weightLog->avg_weight_gain / 1000, 2) . ' کلوگرام' : '', // Avg Weight Gain (Kg)
                'فلاک وزن' => ($data->weightLog) ? round($data->weightLog->aggregated_total_weight / 1000, 2) . ' کلوگرام' : '', // Flock Weight (Kg)
                'خوراک کی کارکردگی' => ($data->weightLog) ? round($data->weightLog->feed_efficiency, 2) . '' : '', // Feed Efficiency
                'FCR' => ($data->weightLog) ? round($data->weightLog->feed_conversion_ratio, 2) . '' : '', // FCR (kept as is, as it's an acronym)
                'FCR Diff' => ($data->weightLog) ? round($data->weightLog->fcr_standard_diff, 2) . '' : '', // FCR Diff
                'CV' => ($data->weightLog) ? round($data->weightLog->coefficient_of_variation, 2) . ' %' : '', // CV (kept as is, as it's an acronym)
                'PEF' => ($data->weightLog) ? round($data->weightLog->production_efficiency_factor, 2) . '' : '', // PEF (kept as is, as it's an acronym)
                'رپورٹر‌' => $data->user->name, // Submit By
            ], 200);
        }

    }
}
