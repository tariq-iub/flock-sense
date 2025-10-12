<?php

namespace App\Models;

//use App\Models\Scopes\FarmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Farm extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
//        static::addGlobalScope(new FarmScope);
    }

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
        return $this->belongsToMany(User::class, 'farm_managers', 'farm_id', 'manager_id')
            ->withPivot('link_date');
    }

    public function staff()
    {
        return $this->belongsToMany(User::class, 'farm_staff', 'farm_id', 'worker_id')
            ->withPivot('link_date');
    }

    public function getFlocksCountAttribute(): int
    {
        $count = 0;

        foreach ($this->sheds as $shed) {
            // Latest flock per shed
            $latestFlock = $shed->flocks->sortByDesc('id')->first();

            // Count only if active (no end_date)
            if ($latestFlock && is_null($latestFlock->end_date)) {
                $count++;
            }
        }

        return $count;
    }

    public function getBirdsCountAttribute(): int
    {
        if (!$this->relationLoaded('sheds')) {
            return 0;
        }

        $totalBirds = 0;

        foreach ($this->sheds as $shed) {
            // Get the latest flock in this shed
            $latestFlock = $shed->flocks->sortByDesc('id')->first();

            // Only count if the flock is active (no end_date)
            if ($latestFlock && is_null($latestFlock->end_date)) {
                $totalBirds += $latestFlock->chicken_count;
            }
        }

        return $totalBirds;
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
