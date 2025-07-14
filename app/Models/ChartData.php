<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChartData extends Model
{
    protected $fillable = [
        'chart_id', 'type', 'day', 'weight', 'daily_gain', 'avg_daily_gain', 'daily_intake', 'cum_intake', 'fcr'
    ];

    public function chart() : BelongsTo
    {
        return $this->belongsTo(Chart::class);
    }
}
