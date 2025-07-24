<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Capability extends Model
{
    protected $fillable = ['name', 'icon', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Devices that have this capability.
     */
    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class)->withTimestamps();
    }

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }
}
