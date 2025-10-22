<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type', 'is_encrypted'];

    protected $casts = [
        'value' => 'json',
        'is_encrypted' => 'boolean',
    ];

    /**
     * Get the decrypted value if the setting is encrypted
     */
    public function getDecryptedValueAttribute()
    {
        if ($this->is_encrypted) {
            return decrypt($this->value);
        }

        return $this->value;
    }

    /**
     * Scope a query to only include settings by group.
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Find a setting by group and key
     */
    public static function getValue($group, $key, $default = null)
    {
        $setting = static::where('group', $group)
            ->where('key', $key)
            ->first();

        if (! $setting) {
            return $default;
        }

        return $setting->decrypted_value;
    }
}
