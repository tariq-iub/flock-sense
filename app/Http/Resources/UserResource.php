<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'user',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'avatar' => $this->media->first(),
                'roles' => $this->getRoleNames(),
                'farms_count' => $this->farms_count,
                $this->mergeWhen($request->routeIs('users.show'), [
                    'sheds_count' => $this->sheds_count,
                    'birds_count' => $this->birds_count,
                ]),
                'email_verified' => ($this->email_verified_at) ? "Yes" : "No",
                'create_at' => $this->created_at->format('Y-m-d H:i:s'),
            ]
        ];
    }
}
