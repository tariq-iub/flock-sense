<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLog extends Model
{
    protected $fillable = [
        'user_id',
        'stripe_payment_id',
        'amount',
        'currency',
        'status',
        'payload'
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
