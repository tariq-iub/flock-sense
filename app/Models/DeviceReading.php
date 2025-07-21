<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceReading extends Model
{
    protected $fillable = [
        'device_id',
        'data',
        'recorded_at',
        'unit',
        'quality',
    ];

    protected $casts = [
        'data' => 'array',
        'quality' => 'array',
        'recorded_at' => 'datetime',
    ];

    public function device() : BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
