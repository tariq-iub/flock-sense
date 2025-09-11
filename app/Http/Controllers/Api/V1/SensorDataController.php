<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\SensorDataResource;
use App\Models\Device;
use App\Models\DeviceAppliance;
use App\Models\Shed;
use App\Models\ShedDevice;
use App\Services\DynamoDbService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorDataController extends ApiController
{
    public function __construct(protected DynamoDbService $dynamoDbService)
    {
        parent::__construct();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_serial' => 'required|string',
        ]);

        $device = Device::where('serial_no', $validated['device_serial'])->first();

        if (!$device) {
            return response()->json(['message' => 'Device not found.'], 404);
        }

        $validated['device_id'] = $device->id;
        $validated['timestamp'] = Carbon::now()->timestamp;
        unset($validated['device_serial']);

        // 👇 Merge dynamic sensor fields back into validated array
        $sensorData = array_merge(
            $validated,
            collect($request->except(['device_serial']))  // All except serial_no
            ->reject(fn($value) => is_null($value)) // Optional: skip nulls
            ->toArray()
        );

        // ✅ Store in DynamoDB
        $this->dynamoDbService->putSensorData($sensorData);

        return response()->json(['message' => 'Sensor data stored successfully.'], 201);
    }

    public function fetchByShed(Request $request, int $shedId)
    {
        $validated = $request->validate([
            'range' => 'nullable|in:latest,last_hour,last_12_hours,day,week,month,custom',
            'from' => 'required_if:range,custom|date_format:Y-m-d H:i:s',
            'to' => 'required_if:range,custom|date_format:Y-m-d H:i:s|after_or_equal:from',
        ]);

        $deviceIds = ShedDevice::where('shed_id', $shedId)->pluck('device_id')->toArray();
        if (empty($deviceIds)) {
            return response()->json(['data' => []], 200);
        }

        $fromTimestamp = null;
        $toTimestamp = null;
        $latest = false;

        if (($validated['range'] ?? null) === 'custom') {
            $fromTimestamp = Carbon::parse($validated['from'])->timestamp;
            $toTimestamp = Carbon::parse($validated['to'])->timestamp;
        } elseif (($validated['range'] ?? null) === 'latest') {
            $latest = true;
        } elseif (!empty($validated['range'])) {
            $fromTimestamp = $this->getTimeRange($validated['range']);
        }

        $results = $this->dynamoDbService->getSensorData(
            $deviceIds,
            $fromTimestamp,
            $toTimestamp,
            $latest
        );

        // 🔥 Remove device_id from each record
        $cleaned = collect($results)->map(function ($record) {
            unset($record['device_id']);
            return $record;
        })->values();

        return response()->json(['data' => $cleaned], 200);
    }

    public function fetchByFarm(Request $request, int $farmId)
    {
        try {
            $validated = $request->validate([
                'range' => 'nullable|in:latest,last_hour,last_12_hours,day,week,month,custom',
                'from' => 'required_if:range,custom|date_format:Y-m-d H:i:s',
                'to' => 'required_if:range,custom|date_format:Y-m-d H:i:s|after_or_equal:from',
            ]);

            $shedIds = Shed::where('farm_id', $farmId)->pluck('id')->toArray();
            if (empty($shedIds)) {
                return response()->json(['data' => []], 200);
            }

            $deviceIds = ShedDevice::whereIn('shed_id', $shedIds)->pluck('device_id')->toArray();
            if (empty($deviceIds)) {
                return response()->json(['data' => []], 200);
            }

            $fromTimestamp = null;
            $toTimestamp = null;
            $latest = false;

            if (($validated['range'] ?? null) === 'custom') {
                $fromTimestamp = Carbon::parse($validated['from'])->timestamp;
                $toTimestamp = Carbon::parse($validated['to'])->timestamp;
            } elseif (($validated['range'] ?? null) === 'latest') {
                $latest = true;
            } elseif (!empty($validated['range'])) {
                $fromTimestamp = $this->getTimeRange($validated['range']);
            }

            $results = $this->dynamoDbService->getSensorData(
                $deviceIds,
                $fromTimestamp,
                $toTimestamp,
                $latest
            );

            // 🔥 Remove device_id from each record
            $cleaned = collect($results)->map(function ($record) {
                unset($record['device_id']);
                return $record;
            })->values();

            return response()->json(['data' => $cleaned], 200);

        } catch (\Throwable $e) {
            Log::error('Error in fetchByFarm', [
                'farm_id' => $farmId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Error fetching farm sensor data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update all appliance statuses & store sensor data
     */
    public function syncDeviceData(Request $request)
    {
        // ✅ Validate request
        $validated = $request->validate([
            'device_serial' => 'required|string',
            'appliances' => 'required|array',
            'appliances.*' => 'required|boolean',
        ]);

        // 🔍 Get device
        $device = Device::where('serial_no', $validated['device_serial'])->firstOrFail();

        $updatedAppliances = [];

        // 🔄 Update or create appliances
        foreach ($validated['appliances'] as $key => $status) {
            $appliance = DeviceAppliance::firstOrNew([
                'device_id' => $device->id,
                'key' => $key,
            ]);

            if (!$appliance->exists) {
                $type = $this->getApplianceTypeFromKey($key);
                $appliance->fill([
                    'type' => $type,
                    'name' => ucfirst($type) . ' ' . strtoupper($key),
                ]);
            }

            $appliance->status = $status;
            $appliance->status_updated_at = now();
            $appliance->save();

            $updatedAppliances[] = $appliance;
        }

        // 📦 Prepare DynamoDB payload
        $sensorData = array_merge(
            [
                'device_id' => $device->id,
                'timestamp' => Carbon::now()->timestamp,
            ],
            collect($request->except(['device_serial']))->toArray()
        );

        // 💾 Store in DynamoDB
        $this->dynamoDbService->putSensorData($sensorData);

        return response()->json([
            'message' => 'Device appliances updated and sensor data stored successfully.',
        ], 201);
    }

    /**
     * Helper: Infer type from key
     */
    private function getApplianceTypeFromKey(string $key): string
    {
        $firstChar = strtolower(substr($key, 0, 1));
        return match ($firstChar) {
            'f' => 'fan',
            'b' => 'brooder',
            'c' => 'cooling_pad',
            'l' => 'light',
            'e' => 'exhaust',
            'h' => 'heater',
            default => 'appliance'
        };
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
