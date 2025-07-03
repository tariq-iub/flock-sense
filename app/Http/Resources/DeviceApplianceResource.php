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
            'id' => $this->id,
            'device_id' => $this->device_id,
            'type' => $this->type,
            'name' => $this->name,
            'channel' => $this->channel,
            'config' => $this->config,
            'status' => $this->status,
            'metrics' => $this->metrics,
            'status_updated_at' => $this->status_updated_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'device' => $this->whenLoaded('device'),

            // For backward compatibility with old status structure
            'status_info' => [
                'status' => $this->status,
                'metrics' => $this->metrics,
                'updated_at' => $this->status_updated_at
            ]
        ];
    }
}
