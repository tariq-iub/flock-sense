<?php

namespace App\Services;

use App\Models\IotDataLog;
use App\Models\Device;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IotDataAggregatorService
{
    public function __construct(protected DynamoDbService $dynamoDbService)
    {
    }

    /**
     * Aggregate sensor data for all devices into time windows.
     */
    public function aggregate(): void
    {
        $devices = Device::all();

        foreach ($devices as $device) {
            $this->aggregateDeviceData($device->id);
        }
    }

    /**
     * Aggregate data for a single device.
     */
    protected function aggregateDeviceData(int $deviceId): void
    {
        $now = Carbon::now();
        $startOfDay = $now->copy()->startOfDay()->timestamp;
        $endOfDay = $now->copy()->endOfDay()->timestamp;

        // Fetch all data for today
        $records = $this->dynamoDbService->getSensorData(
            [$deviceId],
            $startOfDay,
            $endOfDay,
            false
        );

        if (empty($records)) {
            return;
        }

        // Extract parameter keys dynamically (exclude device_id, timestamp)
        $parameters = collect($records[0])->keys()->reject(fn($key) => in_array($key, ['device_id', 'timestamp']));

        // Define time windows
        $windows = [
            'hourly' => 3600,
            '6h' => 21600,
            '12h' => 43200,
            'daily' => 86400,
        ];

        foreach ($windows as $windowName => $windowSeconds) {
            $this->processWindow($deviceId, $records, $parameters, $windowName, $windowSeconds, $startOfDay);
        }
    }

    /**
     * Process one time window and store aggregated logs.
     */
    protected function processWindow(int $deviceId, array $records, $parameters, string $windowName, int $windowSeconds, int $startTimestamp): void
    {
        $buckets = collect($records)->groupBy(function ($record) use ($windowSeconds, $startTimestamp) {
            $bucketIndex = floor(($record['timestamp'] - $startTimestamp) / $windowSeconds);
            return $startTimestamp + $bucketIndex * $windowSeconds;
        });

        foreach ($buckets as $bucketStart => $bucketRecords) {
            foreach ($parameters as $param) {
                $values = collect($bucketRecords)->pluck($param)->filter(fn($v) => is_numeric($v));

                if ($values->isEmpty()) continue;

                IotDataLog::create([
                    'device_id' => $deviceId,
                    'shed_id' => $this->getShedIdForDevice($deviceId),
                    'parameter' => $param,
                    'min_value' => $values->min(),
                    'max_value' => $values->max(),
                    'avg_value' => round($values->avg(), 2),
                    'record_time' => Carbon::createFromTimestamp($bucketStart),
                    'time_window' => $windowName,
                ]);
            }
        }
    }

    /**
     * Lookup shed_id for a device (optional helper).
     */
    protected function getShedIdForDevice(int $deviceId): ?int
    {
        return DB::table('shed_devices')->where('device_id', $deviceId)->value('shed_id');
    }
}
