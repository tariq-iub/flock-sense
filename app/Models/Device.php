<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Device extends Model
{
    protected $fillable = ['serial_no', 'firmware_version', 'capabilities'];

    public function sheds(): BelongsToMany
    {
        return $this->belongsToMany(Shed::class, 'shed_devices')
            ->withPivot('link_date')
            ->withTimestamps();
    }

}
