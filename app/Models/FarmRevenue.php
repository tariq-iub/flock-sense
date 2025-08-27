<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmRevenue extends Model
{
    protected $fillable = [
        'farm_id', 'flock_id', 'shed_id', 'revenue_date', 'category', 'description',
        'quantity', 'unit', 'unit_price', 'amount', 'currency', 'buyer', 'invoice_no', 'created_by',
    ];

    protected $casts = [
        'revenue_date' => 'date',
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saving(function (FarmRevenue $row) {
            if (is_null($row->amount) && ! is_null($row->quantity) && ! is_null($row->unit_price)) {
                $row->amount = round($row->quantity * $row->unit_price, 2);
            }
        });
    }

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function flock(): BelongsTo
    {
        return $this->belongsTo(Flock::class);
    }

    public function shed(): BelongsTo
    {
        return $this->belongsTo(Shed::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
