<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShedParameterLimit extends Model
{
    protected $fillable = [
        'shed_id', 'parameter_name', 'unit', 'min_value', 'max_value', 'avg_value'
    ];

    public function shed()
    {
        return $this->belongsTo(Shed::class);
    }
}
