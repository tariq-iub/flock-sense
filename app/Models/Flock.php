<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Flock extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'shed_id',
        'breed_id',
        'start_date',
        'end_date',
        'chicken_count',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the age of the flock in days.
     *
     * @return int
     */
    public function getAgeAttribute(): int
    {
        $startDate = Carbon::parse($this->start_date);
        $endDate = $this->end_date ? Carbon::parse($this->end_date) : Carbon::now();

        return $startDate->diffInDays($endDate);
    }

    public function shed() : BelongsTo
    {
        return $this->belongsTo(Shed::class);
    }

    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class);
    }

    public function productionLogs(): HasMany
    {
        return $this->hasMany(ProductionLog::class);
    }

    public function weightLog() : HasMany
    {
        return $this->hasMany(WeightLog::class);
    }
}
