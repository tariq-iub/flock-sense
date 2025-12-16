<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Shed extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['farm_id', 'name', 'capacity', 'type', 'description'];

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function flocks(): HasMany
    {
        return $this->hasMany(Flock::class);
    }

    public function latestFlock(): HasOne
    {
        return $this->hasOne(Flock::class)->latestOfMany();
    }

    public function latestFlocks(): HasMany
    {
        return $this->hasMany(Flock::class)->orderByDesc('created_at')->limit(5);
    }

    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class, 'shed_devices')
            ->withPivot('link_date');
    }

    public function shedDevices(): HasMany
    {
        return $this->hasMany(ShedDevice::class);
    }

    public function activeDevices()
    {
        return $this->shedDevices()
            ->where('is_active', true)
            ->with('device');
    }

    public function productionLogs(): HasMany
    {
        return $this->hasMany(ProductionLog::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('sheds') // goes into log_name
            ->logOnly([
                'farm_id',
                'name',
                'capacity',
                'type',
                'description',
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Shed {$this->name} was {$eventName} by ".optional(auth()->user())->name
            );
    }
}
