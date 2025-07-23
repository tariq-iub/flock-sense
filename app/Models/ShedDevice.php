<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShedDevice extends Model
{
    protected $fillable = [
        'shed_id',
        'device_id',
        'location_in_shed',
        'is_active',
        'link_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'link_date' => 'datetime',
    ];

    /**
     * Relationship: Belongs to Shed.
     */
    public function shed() : BelongsTo
    {
        return $this->belongsTo(Shed::class);
    }

    /**
     * Relationship: Belongs to Device.
     */
    public function device() : BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
