<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feed extends Model
{
    protected $fillable = ['title', 'start_day', 'feed_form'];

    public function feedProfiles() : HasMany
    {
        return $this->hasMany(FeedProfile::class);
    }
}
