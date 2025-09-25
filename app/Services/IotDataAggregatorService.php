<?php

namespace App\Services;

use App\Models\IotDataLog;
use App\Models\Device;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            try {
                $this->aggregateDeviceData($device->id);
            } catch (\Throwable $e) {
                Log::error("[IotDataAggregator] Failed for device {$device->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Aggregate data for a single device.
     */
    protected function aggregateDeviceData(int $deviceId): void
    {
        Log::info("[IotDataAggregator] Aggregating device {$deviceId}");

        $now = Carbon::now();
        $dayStart = $now->copy()->startOfDay();
        $dayEnd = $now->copy()->endOfDay();

        // try today's data
        $records = $this->dynamoDbService->getSensorData(
            [$deviceId],
            $dayStart->timestamp,
            $dayEnd->timestamp,
            false
        );

        // if empty, fetch latest and then fetch that day
        if (empty($records)) {
            $latest = $this->dynamoDbService->getSensorData([$deviceId], null, null, true);

            if (empty($latest)) {
                Log::info("[IotDataAggregator] No sensor data at all for device {$deviceId}");
                return;
            }

            $latestTs = (int)($latest[0]['timestamp'] ?? $latest[0]['Timestamp'] ?? 0);
            if (!$latestTs) {
                Log::warning("[IotDataAggregator] Latest record did not contain a timestamp for device {$deviceId}");
                return;
            }

            $dayStart = Carbon::createFromTimestamp($latestTs)->startOfDay();
            $dayEnd = Carbon::createFromTimestamp($latestTs)->endOfDay();

            $records = $this->dynamoDbService->getSensorData(
                [$deviceId],
                $dayStart->timestamp,
                $dayEnd->timestamp,
                false
            );

            if (empty($records)) {
                Log::info("[IotDataAggregator] No records found for latest day ({$dayStart->toDateString()}) for device {$deviceId}");
                return;
            }

            Log::info("[IotDataAggregator] Found " . count($records) . " records for device {$deviceId} on {$dayStart->toDateString()}");
        } else {
            Log::info("[IotDataAggregator] Found today's " . count($records) . " records for device {$deviceId}");
        }

        // extract parameter keys
        $parameters = collect($records[0])
            ->keys()
            ->reject(fn($key) => in_array(strtolower($key), ['device_id', 'timestamp', 'Timestamp']));

        // define time windows
        $windows = [
            'hourly' => 3600,
            '3h' => 3 * 3600,
            '6h' => 6 * 3600,
            '12h' => 12 * 3600,
            'daily' => 24 * 3600,
        ];

        foreach ($windows as $windowName => $windowSeconds) {
            $this->processWindow(
                $deviceId,
                $records,
                $parameters,
                $windowName,
                $windowSeconds,
                $dayStart->timestamp
            );
        }

        // process latest separately
        $this->processLatest($deviceId, $records, $parameters);

        Log::info("[IotDataAggregator] Completed aggregation for device {$deviceId} for day {$dayStart->toDateString()}");
    }

    /**
     * Process one time window and store aggregated logs.
     */
    protected function processWindow(
        int    $deviceId,
        array  $records,
               $parameters,
        string $windowName,
        int    $windowSeconds,
        int    $startTimestamp
    ): void
    {
        $daySeconds = 86400;
        $endTimestamp = $startTimestamp + $daySeconds;

        // pre-build bucket ranges
        $bucketRanges = [];
        for ($ts = $startTimestamp; $ts < $endTimestamp; $ts += $windowSeconds) {
            $bucketRanges[$ts] = [];
        }

        // assign records
        foreach ($records as $record) {
            $recTs = (int)($record['timestamp'] ?? $record['Timestamp'] ?? 0);
            if ($recTs <= 0) {
                continue;
            }

            $bucketIndex = (int)floor(($recTs - $startTimestamp) / $windowSeconds);
            $bucketStart = $startTimestamp + $bucketIndex * $windowSeconds;

            if ($bucketStart < $startTimestamp || $bucketStart >= $endTimestamp) {
                continue;
            }

            $bucketRanges[$bucketStart][] = $record;
        }

        // aggregate per bucket
        foreach ($bucketRanges as $bucketStart => $bucketRecords) {
            foreach ($parameters as $param) {
                $values = collect($bucketRecords)
                    ->map(fn($rec) => $rec[$param] ?? null)
                    ->filter(fn($v) => $v !== null && is_numeric($v))
                    ->map(fn($v) => (float)$v);

                if ($values->isEmpty()) {
                    Log::warning("[IotDataAggregator] Skipping {$param} for device {$deviceId}, bucket {$bucketStart} - no numeric values");
                    continue;
                }

                $min = $values->min();
                $max = $values->max();
                $avg = round($values->avg(), 2);

                $recordTime = Carbon::createFromTimestamp($bucketStart)->format('Y-m-d H:i:s');

                IotDataLog::updateOrCreate(
                    [
                        'device_id' => $deviceId,
                        'parameter' => $param,
                        'time_window' => $windowName,
                        'record_time' => $recordTime,
                    ],
                    [
                        'shed_id' => $this->getShedIdForDevice($deviceId),
                        'min_value' => $min,
                        'max_value' => $max,
                        'avg_value' => $avg,
                    ]
                );
            }
        }
    }

    /**
     * Process the latest single record snapshot.
     */
    protected function processLatest(int $deviceId, array $records, $parameters): void
    {
        $latest = collect($records)->sortByDesc(fn($r) => (int)($r['timestamp'] ?? $r['Timestamp'] ?? 0))->first();

        if (!$latest) {
            Log::warning("[IotDataAggregator] No latest record found for device {$deviceId}");
            return;
        }

        $recordTime = Carbon::createFromTimestamp((int)($latest['timestamp'] ?? $latest['Timestamp']))->format('Y-m-d H:i:s');

        foreach ($parameters as $param) {
            $val = $latest[$param] ?? null;

            if ($val === null || !is_numeric($val)) {
                Log::warning("[IotDataAggregator] Latest record missing {$param} for device {$deviceId}");
                continue;
            }

            $val = (float)$val;

            IotDataLog::updateOrCreate(
                [
                    'device_id' => $deviceId,
                    'parameter' => $param,
                    'time_window' => 'latest',
                    'record_time' => $recordTime,
                ],
                [
                    'shed_id' => $this->getShedIdForDevice($deviceId),
                    'min_value' => $val,
                    'max_value' => $val,
                    'avg_value' => $val,
                ]
            );
        }

        Log::info("[IotDataAggregator] Stored latest snapshot for device {$deviceId} at {$recordTime}");
    }

    /**
     * Lookup shed_id for a device (optional helper).
     */
    protected function getShedIdForDevice(int $deviceId): ?int
    {
        return DB::table('shed_devices')->where('device_id', $deviceId)->value('shed_id');
    }
}
