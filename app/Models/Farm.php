<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Farm extends Model
{
    protected $fillable = ['name', 'address', 'owner_id', 'latitude', 'longitude'];

    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function sheds() : HasMany
    {
        return $this->hasMany(Shed::class);
    }

    public function managers()
    {
        return $this->belongsToMany(User::class, 'farm_managers', 'farm_id', 'manager_id')->withPivot('link_date');
    }

    public function staff()
    {
        return $this->belongsToMany(User::class, 'farm_staff', 'farm_id', 'worker_id')->withPivot('link_date');
    }
}
