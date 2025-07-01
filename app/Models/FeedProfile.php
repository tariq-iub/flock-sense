<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedProfile extends Model
{
    protected $fillable = ['feed_id'];

    public function feed() : BelongsTo
    {
        return $this->belongsTo(Feed::class);
    }
}
