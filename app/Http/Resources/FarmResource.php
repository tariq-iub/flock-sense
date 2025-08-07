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

                'total_live_bird_count' => $this->total_live_bird_count, // Combined live bird count
                'total_daily_mortality' => $this->total_daily_mortality, // Combined daily mortality
                'total_weekly_mortality' => $this->total_weekly_mortality, // Combined weekly mortality
                'total_all_time_mortality' => $this->total_all_time_mortality, // Combined all-time mortality

                'created_at' => $this->created_at ? Carbon::parse($this->created_at)->format('Y-m-d H:i:s') : null,
                'updated_at' => $this->updated_at ? Carbon::parse($this->updated_at)->format('Y-m-d H:i:s') : null,

                'sheds' => ShedResource::collection($this->sheds),

                // Access flocks via sheds using mergeWhen
                $this->mergeWhen(
                    in_array('flocks', explode(',', $request->query('include', ''))),
                    [
                        'flocks' => $this->sheds->map(function ($shed) {
                            return $shed->flocks->map(function ($flock) {
                                return [
                                    'flock_id' => $flock->id,
                                    'live_bird_count' => $flock->live_bird_count,
                                    'daily_mortality' => $flock->daily_mortality,
                                    'weekly_mortality' => $flock->weekly_mortality,
                                    'all_time_mortality' => $flock->all_time_mortality,
                                ];
                            });
                        }),
                    ]
                ),

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
