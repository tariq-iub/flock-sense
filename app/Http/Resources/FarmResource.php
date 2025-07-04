<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
                'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
                $this->mergeWhen($this->relationLoaded('owner'), [
                    'owner' => [
                        'id' => $this->owner->id,
                        'name' => $this->owner->name,
                        'email' => $this->owner->email,
                    ],
                ]),
            ],
        ];
    }
}
