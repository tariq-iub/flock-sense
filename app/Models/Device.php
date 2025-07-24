<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Device extends Model
{
    protected $fillable = [
        'serial_no',
        'model_number',
        'manufacturer',
        'firmware_version',
        'connectivity_type',
        'battery_operated',
        'battery_level',
        'signal_strength',
        'is_online',
        'last_heartbeat',
    ];

    protected $casts = [
        'battery_operated'  => 'boolean',
        'is_online'         => 'boolean',
        'last_heartbeat'    => 'datetime',
        'battery_level'     => 'integer',
        'signal_strength'   => 'integer',
    ];

    /**
     * Many-to-Many relationship with sheds through shed_devices.
     */
    public function sheds(): BelongsToMany
    {
        return $this->belongsToMany(Shed::class, 'shed_devices')
            ->withPivot('link_date', 'is_active', 'location_in_shed')
            ->withTimestamps();
    }

    /**
     * One-to-many relationship to intermediate ShedDevice records.
     */
    public function shedDevices(): HasMany
    {
        return $this->hasMany(ShedDevice::class);
    }

    /**
     * One-to-many relationship to device appliances.
     */
    public function appliances(): HasMany
    {
        return $this->hasMany(DeviceAppliance::class);
    }

    /**
     * One-to-many relationship to readings.
     */
    public function readings(): HasMany
    {
        return $this->hasMany(DeviceReading::class);
    }

    /**
     * One-to-many relationship to device events.
     */
    public function events(): HasMany
    {
        return $this->hasMany(DeviceEvent::class);
    }

    /**
     * Return the active shedDevice relationship, with its shed and farm eager loaded.
     */
    public function currentShed(): ?ShedDevice
    {
        return $this->shedDevices()
            ->where('is_active', true)
            ->with('shed.farm')
            ->latest('link_date')
            ->first();
    }

    /**
     * Capabilities assigned to this device.
     */
    public function capabilities(): BelongsToMany
    {
        return $this->belongsToMany(Capability::class)
            ->withTimestamps();
    }
}
