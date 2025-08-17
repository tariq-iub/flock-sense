<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Farm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'province_id',
        'district_id',
        'city_id',
        'address',
        'owner_id',
        'latitude',
        'longitude',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function sheds(): HasMany
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

    public function getFlocksCountAttribute(): int
    {
        if (!$this->relationLoaded('sheds')) return 0;

        return $this->sheds->sum(function ($shed) {
            return $shed->flocks->count();
        });
    }

    public function getBirdsCountAttribute(): int
    {
        if (!$this->relationLoaded('sheds')) return 0;

        return $this->sheds->sum(function ($shed) {
            return $shed->flocks->sum('chicken_count');
        });
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(Tehsil::class, 'city_id');
    }
}
