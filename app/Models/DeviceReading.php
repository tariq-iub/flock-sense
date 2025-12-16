<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DeviceReading extends Model
{
    use LogsActivity;

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

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('device_readings') // goes into log_name
            ->logOnly([
                'device_id',
                'data',
                'recorded_at',
                'unit',
                'quality',
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Device Reading {$this->name} was {$eventName} by ".optional(auth()->user())->name
            );
    }
}
