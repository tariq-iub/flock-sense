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
        return [
            'type' => 'sensor_data',
            'id' => $this->id ?? null,
            'attributes' => [
                'device_id' => $this->device_id,
                'timestamp' => $this->timestamp,
                'temperature' => $this->temperature ?? null,
                'humidity' => $this->humidity ?? null,
                'nh3' => $this->nh3 ?? null,
                'co2' => $this->co2 ?? null,
                'electricity' => $this->electricity ?? null,
                'created_at' => isset($this->created_at) ? date('Y-m-d H:i:s', $this->timestamp) : null,
            ],
        ];
    }
}
