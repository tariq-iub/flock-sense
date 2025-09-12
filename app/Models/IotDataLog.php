<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IotDataLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'shed_id',
        'device_id',
        'parameter',
        'min_value',
        'max_value',
        'avg_value',
        'record_time',
        'time_window',
    ];
}
