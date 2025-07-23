<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Http\Resources\ManagerResource;
use App\Http\Resources\StaffResource;


class FarmResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'farm',
            'id' => $this->id,

            'attributes' => [
                'name' => $this->name,
                'address' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,

                'sheds_count' => $this->sheds_count,
                'flocks_count' => $this->flocks_count,
                'birds_count' => $this->birds_count,

                'created_at' => $this->created_at ? Carbon::parse($this->created_at)->format('Y-m-d H:i:s') : null,
                'updated_at' => $this->updated_at ? Carbon::parse($this->updated_at)->format('Y-m-d H:i:s') : null,

                'sheds' => ShedResource::collection($this->sheds),

                $this->mergeWhen($this->relationLoaded('owner'), [
                    'owner' => [
                        'id' => $this->owner->id,
                        'name' => $this->owner->name,
                        'email' => $this->owner->email,
                    ],
                ]),

                $this->mergeWhen($this->relationLoaded('managers'), [
                    'managers' => ManagerResource::collection($this->managers),
                ]),

                $this->mergeWhen($this->relationLoaded('staff'), [
                    'staff' => StaffResource::collection($this->staff),
                ]),
            ],
        ];
    }
}
