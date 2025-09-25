<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SensorDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Expecting resource to be a grouped structure:
        // [
        //   'device_id' => 1,
        //   'record_time' => '2025-08-26 06:00:00',
        //   'time_window' => '3h',
        //   'parameters' => [
        //       'humidity' => ['min' => 4.2, 'max' => 4.8, 'avg' => 4.5],
        //       'temp1' => ['min' => 26.7, 'max' => 26.9, 'avg' => 26.8],
        //   ]
        // ]

        return [
            'type' => 'sensor_data',
            'id' => $this->resource['device_id'] . '-' .
                $this->resource['record_time'] . '-' .
                $this->resource['time_window'],
            'attributes' => [
                'device_id' => $this->resource['device_id'],
                'record_time' => $this->resource['record_time'],
                'time_window' => $this->resource['time_window'],
                'parameters' => $this->resource['parameters'] ?? [],
            ],
        ];
    }
}
