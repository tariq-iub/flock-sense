<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DeviceEvent extends Model
{
    use LogsActivity;

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
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        $data = json_decode($this->details, true);
        $shed = Shed::with('farm')->find($data['shed_id']);

        return LogOptions::defaults()
            ->useLogName('device_events') // goes into log_name
            ->logOnly([
                'device' => Device::find($this->device_id)->serial_no,
                'event_type' => $this->event_type,
                'severity' => $this->severity,
                'Shed' => $shed?->name,
                'Farm' => $shed?->farm->name,
                'location' => $data['location'],
                'occurred_at' => $this->occurred_at,
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Device Event {$this->name} was {$eventName} by ".optional(auth()->user())->name
            );
    }
}
