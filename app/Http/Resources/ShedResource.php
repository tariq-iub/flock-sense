<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Http\Resources\SensorDataResource;
use App\Http\Resources\DeviceApplianceResource;

class ShedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'shed',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'capacity' => $this->capacity,
                'type' => $this->type,
                'description' => $this->description,
                'flocks_count' => $this->flocks_count,
                'devices_count' => $this->devices_count,
                'created_at' => $this->created_at ? Carbon::parse($this->created_at)->format('Y-m-d H:i:s') : null,
                'updated_at' => $this->updated_at ? Carbon::parse($this->updated_at)->format('Y-m-d H:i:s') : null,

                $this->mergeWhen($this->relationLoaded('farm'), [
                    'farm' => [
                        'id' => $this->farm->id,
                        'name' => $this->farm->name,
                        'address' => $this->farm->address,
                    ],
                ]),

                // Always include sensor_data and appliances (aggregated from all devices)
                'sensor_data' => $this->whenLoaded('devices', function () {
                    return $this->devices->map(function ($device) {
                        return $device->latest_sensor_data ? new SensorDataResource((object)$device->latest_sensor_data) : null;
                    })->filter()->values();
                }),
                'appliances' => $this->whenLoaded('devices', function () {
                    return $this->devices->flatMap(function ($device) {
                        return $device->appliances ?? [];
                    })->map(function ($appliance) {
                        return new DeviceApplianceResource($appliance);
                    })->values();
                }),

                $this->mergeWhen($request->routeIs('sheds.show'), [
                    'flocks' => $this->whenLoaded('flocks', function () {
                        return $this->flocks->map(function ($flock) {
                            return [
                                'id' => $flock->id,
                                'name' => $flock->name,
                                'start_date' => isset($flock->start_date) && $flock->start_date ? Carbon::parse($flock->start_date)->format('Y-m-d') : null,
                                'end_date' => isset($flock->end_date) && $flock->end_date ? Carbon::parse($flock->end_date)->format('Y-m-d') : null,
                                'chicken_count' => $flock->chicken_count,
                                'status' => $flock->status,
                            ];
                        });
                    }),
                    'devices' => $this->whenLoaded('devices', function () {
                        return $this->devices->map(function ($device) {
                            return [
                                'id' => $device->id,
                                'serial_no' => $device->serial_no,
                                'firmware_version' => $device->firmware_version,
                                'capabilities' => json_decode($device->capabilities, true),
                                'link_date' => isset($device->pivot->link_date) && $device->pivot->link_date ? Carbon::parse($device->pivot->link_date)->format('Y-m-d H:i:s') : null,
                            ];
                        });
                    }),
                ]),
            ],
        ];
    }
}
