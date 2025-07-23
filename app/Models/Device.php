<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    protected $fillable = [
        'serial_no',
        'model_number',
        'manufacturer',
        'firmware_version',
        'capabilities',
        'battery_operated'
    ];

    protected $casts = [
        'capabilities' => 'array',
        'battery_operated' => 'boolean',
    ];

    public function sheds(): BelongsToMany
    {
        return $this->belongsToMany(Shed::class, 'shed_devices')
            ->withPivot('link_date');
    }

    public function appliances(): HasMany
    {
        return $this->hasMany(DeviceAppliance::class);
    }

    public function readings() : HasMany
    {
        return $this->hasMany(DeviceReading::class);
    }

    public function events() : HasMany
    {
        return $this->hasMany(DeviceEvent::class);
    }

    public function shedDevices() : HasMany
    {
        return $this->hasMany(ShedDevice::class);
    }

    public function currentShed()
    {
        return $this->shedDevices()
            ->where('is_active', true)
            ->with('shed.farm')
            ->latest()
            ->first();
    }
}
