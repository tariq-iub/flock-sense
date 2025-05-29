<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Device extends Model
{
    protected $fillable = ['serial_no', 'firmware_version', 'capabilities'];

    public function shed() : HasOneThrough
    {
        return $this->hasOneThrough(Shed::class, ShedDevice::class);
    }
}
