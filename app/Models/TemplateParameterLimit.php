<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateParameterLimit extends Model
{
    protected $fillable = [
        'parameter_name', 'unit', 'min_value', 'max_value', 'avg_value'
    ];
}
