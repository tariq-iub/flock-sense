<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Breed extends Model
{
    protected $fillable = ['name', 'category'];

    public function flocks(): HasMany
    {
        return $this->hasMany(Flock::class);
    }
}
