<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSettingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => 'user_setting',
            'id' => $this->id,
            'attributes' => [
                'security_level' => $this->security_level,
                'backup_frequency' => $this->backup_frequency,
                'language' => $this->language,
                'timezone' => $this->timezone,
                'notifications' => [
                    'email' => $this->notifications_email,
                    'sms' => $this->notifications_sms,
                ],
            ],
        ];
    }
}
