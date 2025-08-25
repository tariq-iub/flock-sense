<?php

namespace App\Http\Controllers\Web;

use App\Exports\IotLogsExport;
use App\Http\Controllers\Controller;
use App\Models\Capability;
use App\Models\Device;
use App\Models\Farm;
use App\Services\DeviceEventService;
use App\Services\DynamoDbService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
        $capabilities = Capability::all();
        $capabilityMap = $capabilities->mapWithKeys(function ($cap) {
            return [strtolower($cap->name) => [
                'name' => $cap->name,
                'icon' => $cap->icon,
                'unit' => $cap->unit,
                'description' => $cap->description,
            ]];
        })->toArray();

        $farms = Farm::with('sheds.flocks')->orderBy('name')->get();
        $logs = collect();
        $farmId = null;
        $device = null;
        $date_range = null;
        $chart = [];

        if ($request->filled('filter.shed_id') && $request->filled('filter.device_id')) {
            $farmId = $request->input('filter.farm_id');
            $date_range = $request->input('filter.date_range');
            $device_id = $request->input('filter.device_id');
            $device = Device::find($device_id);
            [$logs, $days] = $this->logData($device_id, $date_range);

            if ($days <= 7) {
                // Prepare raw data for Chart.js (datetime x-axis)
                $data = collect($logs)->map(function ($row) {
                    return [
                        'timestamp' => Carbon::createFromTimestamp($row['timestamp'])->format('d-m-Y H:i A'),
                        'shed_temp' => (float) $row['temp1'] ?? 0,
                        'brooder_temp' => (float) $row['temp2'] ?? 0,
                        'humidity' => (float) $row['humidity'] ?? 0,
                        'ammonia' => (isset($row['ammonia'])) ? (float) $row['ammonia'] : 0,
                        'carbon_dioxide' => (isset($row['carbon_dioxide'])) ? (float) $row['carbon_dioxide'] : 0,
                        'electricity' => (isset($row['electricity'])) ? (float) $row['electricity'] : 0,
                    ];
                })->sortBy('timestamp')->values()->toArray();
            } else {
                // Group by day and average each metric
                $data = collect($logs)->groupBy(function ($row) {
                    return Carbon::createFromTimestamp($row['timestamp'])->format('Y-m-d');
                })->map(function ($group, $date) {
                    return [
                        'timestamp' => $date,
                        'shed_temp' => round($group->avg('temp1'), 2),
                        'brooder_temp' => round($group->avg('temp2'), 2),
                        'humidity' => round($group->avg('humidity'), 2),
                        'ammonia' => round($group->avg('ammonia'), 2),
                        'carbon_dioxide' => round($group->avg('carbon_dioxide'), 2),
                        'electricity' => round($group->avg('electricity'), 2),
                    ];
                })->sortBy('timestamp')->values()->toArray();
            }

            $chart = [
                'labels' => array_column($data, 'timestamp'),
                'datasets' => [
                    [
                        'label' => 'Shed Temperature (°C)',
                        'data' => array_column($data, 'shed_temp'),
                        'yAxisID' => 'y',
                        'borderColor' => '#f87171',
                        'backgroundColor' => 'rgba(248,113,113,0.1)',
                        'tension' => 0.4,
                    ],
                    [
                        'label' => 'Brooder Temperature (°C)',
                        'data' => array_column($data, 'brooder_temp'),
                        'yAxisID' => 'y',
                        'borderColor' => '#f871bd',
                        'backgroundColor' => 'rgba(248,113,189,0.1)',
                        'tension' => 0.4,
                    ],
                    [
                        'label' => 'Humidity (%)',
                        'data' => array_column($data, 'humidity'),
                        'yAxisID' => 'y',
                        'borderColor' => '#38bdf8',
                        'backgroundColor' => 'rgba(56,189,248,0.1)',
                        'tension' => 0.4,
                    ],
                    [
                        'label' => 'Ammonia (ppm)',
                        'data' => array_column($data, 'ammonia'),
                        'yAxisID' => 'y2',
                        'borderColor' => '#fbbf24',
                        'backgroundColor' => 'rgba(251,191,36,0.1)',
                        'tension' => 0.4,
                    ],
                    [
                        'label' => 'CO₂ (ppm)',
                        'data' => array_column($data, 'carbon_dioxide'),
                        'yAxisID' => 'y2',
                        'borderColor' => '#a3e635',
                        'backgroundColor' => 'rgba(163,230,53,0.1)',
                        'tension' => 0.4,
                    ],
                    [
                        'label' => 'Electricity (kWh)',
                        'data' => array_column($data, 'electricity'),
                        'yAxisID' => 'y3',
                        'borderColor' => '#a78bfa',
                        'backgroundColor' => 'rgba(167,139,250,0.1)',
                        'tension' => 0.4,
                    ],
                ],
            ];
        }

        return view(
            'admin.logs.iot-logs',
            compact(
                'logs',
                'capabilityMap',
                'farms',
                'farmId',
                'device',
                'date_range',
                'chart'
            )
        );
    }

    public function logData($device_id, $date_range): array
    {
        [$start, $end] = explode(' - ', $date_range);
        $startDate = Carbon::createFromFormat('m/d/Y', trim($start))->startOfDay();
        $endDate = Carbon::createFromFormat('m/d/Y', trim($end))->endOfDay();
        $days = $startDate->diffInDays($endDate) + 1;

        $dynamoService = new DynamoDbService;
        $logs = $dynamoService->getSensorData(
            [$device_id],
            (int) $startDate->timestamp,
            (int) $endDate->timestamp,
            false,
            false
        );

        return [$logs, $days];
    }

    public function exportExcel(Request $request)
    {
        $device_id = $request->input('filter.device_id');
        $date_range = $request->input('filter.date_range');
        $device = Device::find($device_id);

        [$logs, $days] = $this->logData($device_id, $date_range);

        return Excel::download(new IotLogsExport($device, $logs), 'device-logs.xlsx');
    }
}
