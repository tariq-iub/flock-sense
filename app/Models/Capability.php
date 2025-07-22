<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Capability extends Model
{
    protected $fillable = ['name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }
}
