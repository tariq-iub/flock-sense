<?php

namespace App\Models;

use App\Traits\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Partner extends Model
{
    use HasMedia;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'company_name',
        'url',
        'introduction',
        'partnership_detail',
        'support_keywords',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'support_keywords' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope for active partners
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered partners
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')
            ->orderBy('company_name');
    }

    /**
     * Search by company name or support keywords
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('company_name', 'like', "%{$searchTerm}%")
            ->orWhereJsonContains('support_keywords', $searchTerm);
    }

    /**
     * Filter by support keywords
     */
    public function scopeByKeyword($query, $keyword)
    {
        return $query->whereJsonContains('support_keywords', $keyword);
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute()
    {
        $media = $this->media()->first();
        if (! $media) {
            return null;
        }

        if (filter_var($media->file_path, FILTER_VALIDATE_URL)) {
            return $media->file_path;
        }

        return $media->url;
    }

    /**
     * Get formatted URL with protocol
     */
    public function getFormattedUrlAttribute()
    {
        if (! $this->url) {
            return null;
        }

        if (! preg_match('~^(?:f|ht)tps?://~i', $this->url)) {
            return 'https://'.$this->url;
        }

        return $this->url;
    }

    /**
     * Check if partner has specific keyword
     */
    public function hasKeyword($keyword)
    {
        return in_array($keyword, $this->support_keywords ?? []);
    }

    /**
     * Add support keyword
     */
    public function addKeyword($keyword)
    {
        $keywords = $this->support_keywords ?? [];
        if (! in_array($keyword, $keywords)) {
            $keywords[] = $keyword;
            $this->support_keywords = $keywords;
        }

        return $this;
    }

    /**
     * Remove support keyword
     */
    public function removeKeyword($keyword)
    {
        $keywords = $this->support_keywords ?? [];
        $this->support_keywords = array_values(array_diff($keywords, [$keyword]));

        return $this;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('partners')
            ->logOnly([
                'company_name',
                'url',
                'introduction',
                'partnership_detail',
                'support_keywords',
                'is_active',
                'sort_order',
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Partner {$this->name} was {$eventName} by ".optional(auth()->user())->name
            );
    }
}
