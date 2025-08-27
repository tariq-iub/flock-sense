<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmExpense extends Model
{
    protected $fillable = [
        'farm_id', 'flock_id', 'shed_id', 'expense_date', 'category', 'description',
        'quantity', 'unit', 'unit_cost', 'amount', 'currency', 'vendor', 'reference_no', 'created_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'quantity' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    // Auto-calc amount if not provided
    protected static function booted()
    {
        static::saving(function (FarmExpense $row) {
            if (is_null($row->amount) && ! is_null($row->quantity) && ! is_null($row->unit_cost)) {
                $row->amount = round($row->quantity * $row->unit_cost, 2);
            }
        });
    }

    public function farm() : BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function shed() : BelongsTo
    {
        return $this->belongsTo(Shed::class);
    }

    public function flock() : BelongsTo
    {
        return $this->belongsTo(Flock::class);
    }

    public function creator() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
