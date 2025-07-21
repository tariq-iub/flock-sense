<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceEvents extends Model
{
    protected $fillable = [
        'device_id',
        'event_type',
        'severity',
        'details',
        'occurred_at',
    ];

    protected $casts = [
        'details' => 'array',
        'occurred_at' => 'datetime',
    ];

    // Relationship to device
    public function device() : BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
