<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Services\DeviceEventService;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function alerts()
    {
        $devices = Device::with(['appliances'])->get();

        return view(
            'admin.devices.alerts',
            compact('devices')
        );
    }

    public function events_data($deviceId)
    {
        [$device, $events] = app(DeviceEventService::class)
            ->eventsData($deviceId);

        $html = view(
            'admin.devices.events-data',
            compact('device', 'events')
        )->render();

        return response()->json(['html' => $html]);
    }

    public function deviceLogs()
    {
        //
    }
}
