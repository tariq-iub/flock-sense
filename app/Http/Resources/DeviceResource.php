<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class DeviceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'device',
            'id' => $this->id,
            'attributes' => [
                'serial_no' => $this->serial_no,
                'firmware_version' => $this->firmware_version,
                'capabilities' => json_decode($this->capabilities, true),
                'sheds_count' => $this->sheds_count,
                'appliances_count' => $this->appliances_count,
                'created_at' => $this->created_at ? Carbon::parse($this->created_at)->format('Y-m-d H:i:s') : null,
                'updated_at' => $this->updated_at ? Carbon::parse($this->updated_at)->format('Y-m-d H:i:s') : null,
                $this->mergeWhen($request->routeIs('devices.show'), [
                    'sheds' => $this->whenLoaded('sheds', function () {
                        return $this->sheds->map(function ($shed) {
                            return [
                                'id' => $shed->id,
                                'name' => $shed->name,
                                'capacity' => $shed->capacity,
                                'type' => $shed->type,
                                'link_date' => isset($shed->pivot->link_date) && $shed->pivot->link_date ? Carbon::parse($shed->pivot->link_date)->format('Y-m-d H:i:s') : null,
                            ];
                        });
                    }),
                    'appliances' => $this->whenLoaded('appliances', function () {
                        return $this->appliances->map(function ($appliance) {
                            return [
                                'id' => $appliance->id,
                                'type' => $appliance->type,
                                'name' => $appliance->name,
                                'channel' => $appliance->channel,
                                'config' => $appliance->config,
                                'status' => $appliance->status,
                                'metrics' => $appliance->metrics,
                                'status_updated_at' => isset($appliance->status_updated_at) && $appliance->status_updated_at ? Carbon::parse($appliance->status_updated_at)->format('Y-m-d H:i:s') : null,
                            ];
                        });
                    }),
                ]),
            ],
        ];
    }
}
