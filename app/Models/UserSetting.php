<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id',
        'security_level',
        'backup_frequency',
        'language',
        'timezone',
        'notifications_email',
        'notifications_sms',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
