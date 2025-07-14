<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chart extends Model
{
    protected $fillable = [
        'chart_name', 'source', 'description', 'unit', 'settings'
    ];

    public function data() : HasMany
    {
        return $this->hasMany(ChartData::class);
    }
}
