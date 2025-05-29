<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Shed extends Model
{
    protected $fillable = ['farm_id', 'name', 'capacity'];

    public function farm() : BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function devices() : HasManyThrough
    {
        return $this->hasManyThrough(Device::class, ShedDevice::class);
    }
}
