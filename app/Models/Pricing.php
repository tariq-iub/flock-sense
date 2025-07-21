<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pricing extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'currency',
        'billing_interval',
        'trial_period_days',
        'max_farms',
        'max_sheds',
        'max_flocks',
        'max_devices',
        'max_users',
        'feature_flags',
        'meta',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'feature_flags' => 'array',
        'meta' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'max_farms' => 'integer',
        'max_sheds' => 'integer',
        'max_flocks' => 'integer',
        'max_devices' => 'integer',
        'max_users' => 'integer',
        'trial_period_days' => 'integer',
    ];

    // Example for future extensibility:
    // public function addons() { return $this->hasMany(PricingAddon::class); }

    // Scope for active packages
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
