<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Shed extends Model
{
    protected $fillable = ['farm_id', 'name', 'capacity', 'type', 'description'];

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function flocks(): HasMany
    {
        return $this->hasMany(Flock::class);
    }

    public function latestFlocks() : HasMany
    {
        return $this->hasMany(Flock::class)->orderByDesc('created_at')->limit(10);
    }

    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class, 'shed_devices')
            ->withPivot('link_date');
    }

}
