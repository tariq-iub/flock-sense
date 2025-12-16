<?php

namespace App\Services;

use App\Models\Device;
use App\Models\DeviceEvent;
use Illuminate\Support\Carbon;

class DeviceEventService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Log a device event in a reusable, centralized way.
     *
     * @param  string  $eventType  (e.g., linked, delinked, threshold_breach)
     * @param  array  $details  Additional event details (JSON stored)
     * @param  string|null  $severity  (info, warning, critical)
     * @param  Carbon|string|null  $occurredAt
     */
    public function logEvent(
        int $deviceId,
        string $eventType,
        array $details = [],
        ?string $severity = 'info',
        $occurredAt = null
    ): DeviceEvent {
        return DeviceEvent::create([
            'device_id' => $deviceId,
            'event_type' => $eventType,
            'severity' => $severity,
            'details' => json_encode($details),
            'occurred_at' => $occurredAt ?? now(),
        ]);
    }

    public function eventsData($deviceId)
    {
        $device = Device::find($deviceId);
        $events = DeviceEvent::where('device_id', $deviceId)
            ->orderBy('occurred_at', 'desc')
            ->get();

        return [$device, $events];
    }
}
