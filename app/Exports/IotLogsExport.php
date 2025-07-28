<?php

namespace App\Exports;

use App\Models\Device;
use App\Services\DynamoDbService;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IotLogsExport implements FromCollection, WithHeadings
{
    protected Device $device;
    protected $logs;

    public function __construct(Device $device, $logs)
    {
        $this->device = $device;
        $this->logs = $logs;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Load relations if needed and select fields you want to export
        return collect($this->logs)->map(function($log) {
            return [
                'farm' => $this->device->currentShed()->shed->farm->name,
                'shed' => $this->device->currentShed()->shed->name,
                'device_id' => $this->device->serial_no,
                'created_at'  => Carbon::createFromTimestamp($log['timestamp'])->format('d-m-Y H:i A'),
                'temperature' => (isset($log['temperature'])) ? (float) $log['temperature'] : 0,
                'humidity' => (isset($log['humidity'])) ? (float) $log['humidity'] : 0,
                'ammonia' => (isset($log['ammonia'])) ? (float) $log['ammonia'] : 0,
                'carbon_dioxide' => (isset($log['carbon_dioxide'])) ? (float) $log['carbon_dioxide'] : 0,
                'electricity' => (isset($log['electricity'])) ? (float) $log['electricity'] : 0,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Farm', 'Shed', 'Device Id', 'Log Date', 'Temperature', 'Humidity (%)', 'Ammonia (ppm)', 'Carbon Dioxide (ppm)', 'Electricity (kWh)'
        ];
    }
}
