<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\SensorDataResource;
use App\Models\Device;
use App\Models\Shed;
use App\Models\ShedDevice;
use App\Services\DynamoDbService;
use Illuminate\Http\Request;

class SensorDataController extends ApiController
{
    public function __construct(protected DynamoDbService $dynamoDbService) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'serial_no' => 'required|string|exists:devices,serial_no',
            'timestamp' => 'required|integer',
        ]);

        $device = Device::where('serial_no', $validated['serial_no'])->first();

        $sensorData = $request->except(['serial_no']);
        $sensorData['device_id'] = $device->id;

        $this->dynamoDbService->putSensorData($sensorData);

        return response()->json(['message' => 'Sensor data stored successfully.'], 201);
    }

    public function fetchByShed(Request $request, int $shedId)
    {
        $validated = $request->validate([
            'range' => 'required|in:latest,last_hour,last_12_hours,day,week,month'
        ]);

        $deviceIds = ShedDevice::where('shed_id', $shedId)->pluck('device_id')->toArray();

        $fromTimestamp = $this->getTimeRange($validated['range']);

        $results = $this->dynamoDbService->getSensorData($deviceIds, $fromTimestamp, $validated['range'] === 'latest');

        return SensorDataResource::collection($results);
    }

    public function fetchByFarm(Request $request, int $farmId)
    {
        $validated = $request->validate([
            'range' => 'required|in:latest,last_hour,last_12_hours,day,week,month'
        ]);

        $shedIds = Shed::where('farm_id', $farmId)->pluck('id');

        $deviceIds = ShedDevice::whereIn('shed_id', $shedIds)->pluck('device_id')->toArray();

        $fromTimestamp = $this->getTimeRange($validated['range']);

        $results = $this->dynamoDbService->getSensorData($deviceIds, $fromTimestamp, $validated['range'] === 'latest');

        return SensorDataResource::collection($results);
    }

    private function getTimeRange(string $range): ?int
    {
        return match($range) {
            'last_hour' => now()->subHour()->timestamp,
            'last_12_hours' => now()->subHours(12)->timestamp,
            'day' => now()->subDay()->timestamp,
            'week' => now()->subWeek()->timestamp,
            'month' => now()->subMonth()->timestamp,
            default => null,
        };
    }
}
