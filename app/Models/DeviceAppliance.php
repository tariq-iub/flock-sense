<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DeviceAppliance extends Model
{
    use LogsActivity;

    protected $fillable = [
        'device_id',
        'key',
        'type',
        'name',
        'channel',
        'config',
        'status',
        'metrics',
        'status_updated_at',
    ];

    protected $casts = [
        'config' => 'array',
        'metrics' => 'array',
        'status' => 'boolean',
        'status_updated_at' => 'datetime',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Helper method to update status with timestamp
     */
    public function updateStatus(bool $status, ?array $metrics = null): void
    {
        $this->update([
            'status' => $status,
            'metrics' => $metrics,
            'status_updated_at' => now(),
        ]);
    }

    /**
     * Helper method to get status info
     */
    public function getStatusInfo(): array
    {
        return [
            'status' => $this->status,
            'metrics' => $this->metrics,
            'updated_at' => $this->status_updated_at,
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('device_appliances') // goes into log_name
            ->logOnly([
                'device' => Device::find('device_id')->serial_no,
                'name' => $this->name,
                'channel' => $this->channel,
                'config' => $this->config,
                'status' => $this->status,
                'metrics' => $this->metrics,
                'status_updated_at' => $this->status_updated_at,
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Device appliance {$this->name} was {$eventName} by ".optional(auth()->user())->name
            );
    }
}
