<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Models\ProductionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductionLogController extends ApiController
{
    public function index()
    {
        $logs = ProductionLog::with(['shed', 'flock', 'user'])->latest()->get();
        return response()->json($logs);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shed_id' => 'required|exists:sheds,id',
            'flock_id' => 'required|exists:flocks,id',
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
            'is_vaccinated' => 'boolean',
        ]);

        $validated['user_id'] = Auth::user()->id;
        $log = ProductionLog::create($validated);

        return response()->json($log, 201);
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
}
