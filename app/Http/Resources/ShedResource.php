<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
                'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
                $this->mergeWhen($this->relationLoaded('farm'), [
                    'farm' => [
                        'id' => $this->farm->id,
                        'name' => $this->farm->name,
                        'address' => $this->farm->address,
                    ],
                ]),
                $this->mergeWhen($request->routeIs('sheds.show'), [
                    'flocks' => $this->whenLoaded('flocks', function () {
                        return $this->flocks->map(function ($flock) {
                            return [
                                'id' => $flock->id,
                                'name' => $flock->name,
                                'start_date' => $flock->start_date?->format('Y-m-d'),
                                'end_date' => $flock->end_date?->format('Y-m-d'),
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
                                'link_date' => $device->pivot->link_date?->format('Y-m-d H:i:s'),
                            ];
                        });
                    }),
                ]),
            ],
        ];
    }
}
