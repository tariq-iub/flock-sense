<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Shortcut extends Model
{
    protected $fillable = [
        'title',
        'url',
        'icon',
        'group',
        'default',
    ];

    protected $casts = [
        'default' => 'boolean',
    ];

    /**
     * Get the users that have this shortcut
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'shortcut_user')
            ->withTimestamps();
    }

    /**
     * Check if shortcut belongs to admin group
     */
    public function isAdminShortcut(): bool
    {
        return $this->group === 'admin';
    }

    /**
     * Check if shortcut belongs to user group
     */
    public function isUserShortcut(): bool
    {
        return $this->group === 'user';
    }

    /**
     * Get all available groups
     */
    public static function getAvailableGroups(): array
    {
        return ['admin', 'user'];
    }

    /**
     * Scope for admin shortcuts
     */
    public function scopeAdmin($query)
    {
        return $query->where('group', 'admin');
    }

    /**
     * Scope for user shortcuts
     */
    public function scopeUserGroup($query)
    {
        return $query->where('group', 'user');
    }

    /**
     * Scope for default shortcuts
     */
    public function scopeDefault($query)
    {
        return $query->where('default', true);
    }

    /**
     * Get all shortcuts for a specific group
     */
    public static function getByGroup(string $group)
    {
        return self::where('group', $group)->get();
    }

    /**
     * Get default shortcuts for a specific group
     */
    public static function getDefaultByGroup(string $group)
    {
        return self::where('group', $group)
            ->where('default', true)
            ->get();
    }
}
