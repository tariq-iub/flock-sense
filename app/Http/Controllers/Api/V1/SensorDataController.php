<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SensorDataResource;
use App\Models\Device;
use App\Models\Shed;
use App\Models\ShedDevice;
use App\Services\DynamoDbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorDataController extends Controller
{
    public function __construct(protected DynamoDbService $dynamoDbService)
    {
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'serial_no' => 'required|string',
            'timestamp' => 'required|integer',
        ]);

        $device = Device::where('serial_no', $validated['serial_no'])->first();

        if (!$device) {
            return response()->json(['message' => 'Device not found.'], 404);
        }

        $validated['device_id'] = $device->id;
        unset($validated['serial_no']);

        $this->dynamoDbService->putSensorData($validated);

        return response()->json(['message' => 'Sensor data stored successfully.'], 201);
    }

    public function fetchByShed(Request $request, int $shedId)
    {
        $validated = $request->validate([
            'range' => 'required|in:latest,last_hour,last_12_hours,day,week,month'
        ]);

        // Get all devices in this shed
        $deviceIds = ShedDevice::where('shed_id', $shedId)->pluck('device_id')->toArray();

        if (empty($deviceIds)) {
            return response()->json(['data' => []], 200);
        }

        $fromTimestamp = $this->getTimeRange($validated['range']);
        $results = $this->dynamoDbService->getSensorData($deviceIds, $fromTimestamp, $validated['range'] === 'latest');

        return response()->json(['data' => SensorDataResource::collection($results)], 200);
    }

    public function fetchByFarm(Request $request, int $farmId)
    {
        try {
            $validated = $request->validate([
                'range' => 'required|in:latest,last_hour,last_12_hours,day,week,month'
            ]);

            // DEBUG: Log the farm ID being requested
            Log::info('DEBUG fetchByFarm - Farm ID requested:', ['farm_id' => $farmId]);

            // Get all sheds under this farm
            $shedIds = Shed::where('farm_id', $farmId)->pluck('id')->toArray();
            Log::info('DEBUG fetchByFarm - Shed IDs found:', ['shed_ids' => $shedIds]);

            if (empty($shedIds)) {
                Log::info('DEBUG fetchByFarm - No sheds found for farm', ['farm_id' => $farmId]);
                return response()->json(['data' => [], 'debug' => 'No sheds found for this farm'], 200);
            }

            // Get all devices in those sheds
            $deviceIds = ShedDevice::whereIn('shed_id', $shedIds)->pluck('device_id')->toArray();
            Log::info('DEBUG fetchByFarm - Device IDs found:', ['device_ids' => $deviceIds]);

            if (empty($deviceIds)) {
                Log::info('DEBUG fetchByFarm - No devices found in sheds', ['shed_ids' => $shedIds]);
                return response()->json(['data' => [], 'debug' => 'No devices found in farm sheds'], 200);
            }

            $fromTimestamp = $this->getTimeRange($validated['range']);
            Log::info('DEBUG fetchByFarm - Querying DynamoDB:', [
                'device_ids' => $deviceIds,
                'from_timestamp' => $fromTimestamp,
                'is_latest' => $validated['range'] === 'latest'
            ]);

            $results = $this->dynamoDbService->getSensorData($deviceIds, $fromTimestamp, $validated['range'] === 'latest');
            Log::info('DEBUG fetchByFarm - DynamoDB results count:', ['count' => count($results)]);

            return response()->json(['data' => SensorDataResource::collection($results)], 200);

        } catch (\Throwable $e) {
            Log::error('Error in fetchByFarm', [
                'farm_id' => $farmId,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'message' => 'Error fetching farm sensor data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getTimeRange(string $range): ?int
    {
        return match ($range) {
            'latest' => null,
            'last_hour' => now()->subHour()->timestamp,
            'last_12_hours' => now()->subHours(12)->timestamp,
            'day' => now()->subDay()->timestamp,
            'week' => now()->subWeek()->timestamp,
            'month' => now()->subMonth()->timestamp,
            default => null,
        };
    }
}
