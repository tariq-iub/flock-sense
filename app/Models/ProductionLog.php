<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'shed_id',
        'flock_id',
        'age',
        'day_mortality_count',
        'night_mortality_count',
        'net_count',
        'livability',
        'day_feed_consumed',
        'night_feed_consumed',
        'avg_feed_consumed',
        'day_water_consumed',
        'night_water_consumed',
        'avg_water_consumed',
        'is_vaccinated',
        'user_id',
    ];

    public function flock() : BelongsTo
    {
        return $this->belongsTo(Flock::class);
    }

    public function shed() : BelongsTo
    {
        return $this->belongsTo(Shed::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function weightLog() : HasOne
    {
        return $this->hasOne(WeightLog::class);
    }
}
