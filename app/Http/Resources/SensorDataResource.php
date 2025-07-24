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
        // Convert resource to array safely
        $data = is_object($this->resource)
            ? (array)$this->resource
            : $this->resource;

        return [
            'type' => 'sensor_data',
            'id' => $data['device_id'] ?? null,
            'attributes' => array_merge(
                [
                    'device_id' => $data['device_id'] ?? null,
                    'timestamp' => $data['timestamp'] ?? null,
                ],
                collect($data)
                    ->except(['device_id', 'timestamp'])
                    ->toArray()
            ),
        ];
    }
}
