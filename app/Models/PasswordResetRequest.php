<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    //
    protected $fillable = [
        'email', 'otp', 'is_verified', 'verified_at', 'reset_at',
        'attempts', 'ip_address', 'user_agent', 'expires_at'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'reset_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
