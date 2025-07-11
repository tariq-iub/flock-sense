<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => 'manager',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'avatar' => $this->media->first(),
                'roles' => $this->getRoleNames(),
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            ]
        ];
    }
} 