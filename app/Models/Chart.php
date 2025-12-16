<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Chart extends Model
{
    use LogsActivity;

    protected $fillable = [
        'chart_name',
        'source',
        'description',
        'unit',
        'settings',
    ];

    public function data(): HasMany
    {
        return $this->hasMany(ChartData::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('charts') // goes into log_name
            ->logOnly([
                'chart_name',
                'source',
                'description',
                'unit',
                'settings',
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Chart {$this->name} was {$eventName} by ".optional(auth()->user())->name
            );
    }
}
