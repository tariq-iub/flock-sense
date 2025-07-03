<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flock extends Model
{
    protected $fillable = [
        'name',
        'shed_id',
        'breed_id',
        'start_date',
        'end_date',
        'chicken_count',
    ];
}
