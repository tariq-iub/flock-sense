<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceApplianceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'device_appliance',
            'id' => $this->id,
            'attributes' => [
                'type' => $this->type,
                'name' => $this->name,
                'channel' => $this->channel,
                'config' => $this->config,
                'status' => $this->status,
                'metrics' => $this->metrics,
                'status_updated_at' => $this->status_updated_at?->format('Y-m-d H:i:s'),
                'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
                $this->mergeWhen($this->relationLoaded('device'), [
                    'device' => [
                        'id' => $this->device->id,
                        'serial_no' => $this->device->serial_no,
                        'firmware_version' => $this->device->firmware_version,
                        'capabilities' => json_decode($this->device->capabilities, true),
                    ],
                ]),
            ],
        ];
    }
}
