<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceAppliance extends Model
{
    protected $fillable = [
        'device_id',
        'type',
        'name',
        'channel',
        'config',
        'status',
        'metrics',
        'status_updated_at'
    ];

    protected $casts = [
        'config' => 'array',
        'metrics' => 'array',
        'status' => 'boolean',
        'status_updated_at' => 'datetime'
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Helper method to update status with timestamp
     */
    public function updateStatus(bool $status, array $metrics = null): void
    {
        $this->update([
            'status' => $status,
            'metrics' => $metrics,
            'status_updated_at' => now()
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
            'updated_at' => $this->status_updated_at
        ];
    }
}
