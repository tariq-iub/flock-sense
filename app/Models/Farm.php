<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Farm extends Model
{
    protected $fillable = ['name', 'address', 'owner_id'];

    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function sheds() : HasMany
    {
        return $this->hasMany(Shed::class);
    }
}
