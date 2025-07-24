<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Farm;
use App\Services\DeviceEventService;
use App\Services\DynamoDbService;
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

    public function deviceLogs(Request $request)
    {
        $farms = Farm::with('sheds.flocks')->orderBy('name')->get();
        $logs = collect();
        $farmId = null;

        $dynamoService = new DynamoDbService();

        $logs = $dynamoService->getSensorData(
                [1],
                (int)now()->subDay(7)->timestamp
        );

        return $logs;
        return view('admin.devices.logs');
    }
}
