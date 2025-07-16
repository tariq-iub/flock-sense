<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionLog extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function ($log) {
            $previousLogs = static::where('flock_id', $log->flock_id);
            $feedSum = $previousLogs->sum('feed_consumed') + $log->feed_consumed;
            $weightSum = $previousLogs->sum('total_weight') + $log->total_weight;
            $log->fcr = ($weightSum > 0) ? round($feedSum / $weightSum, 3) : 0;
        });
    }

    protected $fillable = [
        'shed_id',
        'flock_id',
        'chicken_count',
        'age',
        'mortality_count',
        'total_weight',
        'water_consumed',
        'feed_consumed',
        'day_lowest_temperature',
        'day_lowest_temperature_time',
        'day_peak_temperature',
        'day_peak_temperature_time',
        'day_lowest_humidity',
        'day_lowest_humidity_time',
        'day_peak_humidity',
        'day_peak_humidity_time',
        'fcr',
        'fcr_standard_diff',
        'vet_visited',
        'is_vaccinated',
        'user_id'
    ];

    protected $casts = [
        'vet_visited' => 'boolean',
        'is_vaccinated' => 'boolean',
        'day_lowest_temperature_time' => 'datetime',
        'day_peak_temperature_time' => 'datetime',
        'day_lowest_humidity_time' => 'datetime',
        'day_peak_humidity_time' => 'datetime'
    ];

    public function shed() : BelongsTo
    {
        return $this->belongsTo(Shed::class);
    }

    public function flock() : BelongsTo
    {
        return $this->belongsTo(Flock::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
