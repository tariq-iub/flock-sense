<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Newsletter extends Model
{
    protected $fillable = [
        'subject',
        'preview_text',
        'content_html',
        'content_text',
        'status',
        'send_at',
        'target_count',
        'sent_count',
        'started_at',
        'completed_at',
        'last_error',
        'created_by',
    ];

    protected $casts = [
        'send_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function deliveries(): HasMany
    {
        return $this->hasMany(NewsletterDelivery::class);
    }
}
