<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ExpenseHead extends Model
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category',
        'item',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all unique expense categories.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function categories()
    {
        return self::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }

    public static function categoriesWithitems()
    {
        return self::where('is_active', true)
            ->orderBy('category')
            ->orderBy('item')
            ->get()
            ->groupBy('category')
            ->map(function ($items, $categoryName) {
                return [
                    'id' => $items->first()->id, // Assuming the first item's ID can represent the category ID
                    'category' => $categoryName,
                    'items' => $items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'item' => $item->item,
                            'description' => $item->description,
                        ];
                    })->filter(function ($item) {
                        return ! is_null($item['item']);
                    })->values(),
                ];
            })->values();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('expense-head') // goes into log_name
            ->logOnly([
                'category',
                'item',
                'description',
                'is_active',
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Expense head {$this->name} was {$eventName} by ".optional(auth()->user())->name);
    }
}
