<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
