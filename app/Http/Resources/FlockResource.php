<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class FlockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'flock',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'start_date' => $this->start_date ? Carbon::parse($this->start_date)->format('Y-m-d') : null,
                'end_date' => $this->end_date?->format('Y-m-d'),
                'chicken_count' => $this->chicken_count,
                'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),

                $this->mergeWhen($this->relationLoaded('shed'), [
                    'shed' => [
                        'id' => $this->shed->id,
                        'name' => $this->shed->name,
                        'capacity' => $this->shed->capacity,
                        'type' => $this->shed->type,
                    ],
                ]),

                $this->mergeWhen($this->relationLoaded('breed'), [
                    'breed' => [
                        'id' => $this->breed->id,
                        'name' => $this->breed->name,
                    ],
                ]),
            ],
        ];
    }
}
