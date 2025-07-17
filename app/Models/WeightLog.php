<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeightLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_log_id',
        'weighted_chickens_count',
        'total_weight',
        'avg_weight',
        'avg_weight_gain',
        'aggregated_total_weight',
        'feed_efficiency',
        'feed_conversion_ratio',
        'adjusted_feed_conversion_ratio',
        'fcr_standard_diff',
        'standard_deviation',
        'coefficient_of_variation',
        'production_efficiency_factor',
    ];

    public function productionLog() : BelongsTo
    {
        return $this->belongsTo(ProductionLog::class);
    }

    public function flock() : BelongsTo
    {
        return $this->belongsTo(Flock::class);
    }
}
