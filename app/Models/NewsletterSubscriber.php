<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NewsletterSubscriber extends Model
{
    use LogsActivity;

    protected $fillable = [
        'email',
        'status',
        'confirmed_at',
        'unsubscribe_token',
        'source',
        'ip_address',
        'user_agent',
        'last_sent_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'last_sent_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $subscriber) {
            $subscriber->unsubscribe_token = $subscriber->unsubscribe_token ?: Str::random(48);
        });
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'subscribed');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('newsletters_subscriber') // goes into log_name
            ->logOnly([
                'email',
                'status',
                'confirmed_at',
                'unsubscribe_token',
                'source',
                'ip_address',
                'user_agent',
                'last_sent_at',
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Subscriber {$this->name} was {$eventName}."
            );
    }
}
