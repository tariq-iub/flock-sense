<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class IotDataLog extends Model
{
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

    protected $casts = [
        'record_time' => 'datetime',
        'min_value' => 'float',
        'max_value' => 'float',
        'avg_value' => 'float',
    ];

    public function scopeForShed(Builder $query, int $shedId): Builder
    {
        return $query->where('shed_id', $shedId);
    }

    public function scopeForDevice(Builder $query, int $shedId, int $deviceId): Builder
    {
        return $query->where('shed_id', $shedId)
            ->where('device_id', $deviceId);
    }

    public function scopeHourly(Builder $query): Builder
    {
        return $query->where('time_window', 'hourly');
    }

    public function scopeForPeriod(Builder $query, $start_date, $end_date): Builder
    {
        $start_date = Carbon::parse($start_date)->startOfDay()->toDateTimeString();
        $end_date = Carbon::parse($end_date)->endOfDay()->toDateTimeString();

        return $query->whereBetween('record_time', [$start_date, $end_date]);
    }
}
