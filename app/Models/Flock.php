<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function shed() : BelongsTo
    {
        return $this->belongsTo(Shed::class);
    }

    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class);
    }
}
