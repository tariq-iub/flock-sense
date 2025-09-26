<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Services\GraphDataService;
use Illuminate\Http\Request;

class GraphDataController extends ApiController
{
    protected $graphDataService;

    public function __construct(GraphDataService $graphDataService)
    {
        parent::__construct();
        $this->graphDataService = $graphDataService;
    }

    /**
     * 1. Daily Mortality Rate
     * Params: flock_id (required)
     */
    public function mortalityRate(Request $request)
    {
        $request->validate([
            'flock_id' => 'required|integer|exists:flocks,id',
        ]);

        $data = $this->graphDataService->getDailyMortalityRate($request->flock_id);

        return response()->json(['data' => $data]);
    }

    /**
     * 2. Daily ADG & Aggregated Weight
     * Params: flock_id (required)
     */
    public function adgAndWeight(Request $request)
    {
        $request->validate([
            'flock_id' => 'required|integer|exists:flocks,id',
        ]);

        $data = $this->graphDataService->getDailyAdgAndWeight($request->flock_id);

        return response()->json(['data' => $data]);
    }

    /**
     * 3. Feed/Weight with Cumulative Metrics
     * Params: farm_id (optional), start_date (optional), end_date (optional)
     */
    public function feedWeightCumulative(Request $request)
    {
        $request->validate([
            'farm_id' => 'nullable|integer|exists:farms,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $data = $this->graphDataService->getFeedWeightCumulativeData(
            $request->farm_id,
            $request->start_date,
            $request->end_date
        );

        return response()->json(['data' => $data]);
    }

    /**
     * 4. Daily FCR
     * Params: flock_id (required)
     */
    public function fcr(Request $request)
    {
        $request->validate([
            'flock_id' => 'required|integer|exists:flocks,id',
        ]);

        $data = $this->graphDataService->getDailyFcr($request->flock_id);
        $latestRow = collect($data)->last();

        return response()->json([
            'data' => $data,
            'latestRow' => $latestRow,
        ]);
    }

    /**
     * 5. Water-to-Feed Ratio
     * No params
     */
    public function waterToFeedRatio()
    {
        $data = $this->graphDataService->getWaterToFeedRatio();

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * 6. Uniformity (CV-based)
     * No params
     */
    public function uniformity()
    {
        $data = $this->graphDataService->getUniformity();

        return response()->json(['data' => $data]);
    }

    /**
     * 7. Vaccination History
     * Params: flock_id (required)
     */
    public function vaccinationHistory(Request $request)
    {
        $request->validate([
            'flock_id' => 'required|integer|exists:flocks,id',
        ]);

        $data = $this->graphDataService->getVaccinationHistory($request->flock_id);

        return response()->json(['data' => $data]);
    }

    /**
     * 8. Feed Consumption History
     * Params: flock_id (required)
     */
    public function feedConsumptionHistory(Request $request)
    {
        $request->validate([
            'flock_id' => 'required|integer|exists:flocks,id',
        ]);

        $data = $this->graphDataService->getFeedConsumptionHistory($request->flock_id);

        return response()->json(['data' => $data]);
    }

    /**
     * 9. Water Consumption History
     * Params: flock_id (required)
     */
    public function waterConsumptionHistory(Request $request)
    {
        $request->validate([
            'flock_id' => 'required|integer|exists:flocks,id',
        ]);

        $data = $this->graphDataService->getWaterConsumptionHistory($request->flock_id);

        return response()->json(['data' => $data]);
    }
}
