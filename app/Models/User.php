<?php

namespace App\Models;

use App\Traits\HasMedia;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Billable;
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use HasMedia;
    use HasRoles;
    use Notifiable;

    protected $dates = ['trial_ends_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'password_reset_required',
        'pricing_id',
        'trial_ends_at',
        'subscription_status',
        'stripe_customer_id',
        'stripe_subscription_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_reset_required' => 'boolean',
        ];
    }

    public function farms(): HasMany
    {
        return $this->hasMany(Farm::class, 'owner_id');
    }

    public function managedFarms()
    {
        return $this->belongsToMany(Farm::class, 'farm_managers', 'manager_id', 'farm_id')
            ->withPivot('link_date');
    }

    public function staffFarms()
    {
        return $this->belongsToMany(Farm::class, 'farm_staff', 'worker_id', 'farm_id')
            ->withPivot('link_date');
    }

    public function getShedsCountAttribute()
    {
        if (! $this->relationLoaded('farms')) {
            return 0;
        }

        return $this->farms->sum('sheds_count');
    }

    public function getBirdsCountAttribute()
    {
        if (! $this->relationLoaded('farms')) {
            return 0;
        }

        return $this->farms->sum(function ($farm) {
            return $farm->sheds->sum(function ($shed) {
                return $shed->flocks->sum('chicken_count');
            });
        });
    }

    public function settings(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }

    public function pricing()
    {
        return $this->belongsTo(Pricing::class);
    }

    public function paymentLogs()
    {
        return $this->hasMany(PaymentLog::class);
    }

    // Helper: Days left in trial
    public function trialDaysLeft()
    {
        return $this->trial_ends_at ? now()->diffInDays($this->trial_ends_at, false) : 0;
    }

    public function isOnTrial()
    {
        return $this->trial_ends_at && now()->lt($this->trial_ends_at);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()->active()->first();
    }
}
