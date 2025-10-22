<?php

use App\Services\SettingsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

if (! function_exists('format_date_for_display')) {
    /**
     * Format date for display in d/m/Y format
     *
     * @param  mixed  $date
     */
    function format_date_for_display($date, string $format = 'd/m/Y'): ?string
    {
        try {
            if (empty($date)) {
                return null;
            }

            return Carbon::parse($date)->format($format);
        } catch (\Exception $e) {
            Log::error('Date formatting error: '.$e->getMessage(), [
                'date' => $date,
                'format' => $format,
            ]);

            return null;
        }
    }
}

if (! function_exists('settings')) {
    /**
     * Get setting value by key with optional default value
     *
     * @param  string  $key  Format: "group.key" or just "key"
     * @param  mixed  $default
     * @return mixed
     */
    function settings(string $key, $default = null)
    {
        try {
            return app(SettingsService::class)->get($key, $default);
        } catch (\Exception $e) {
            Log::error('Settings helper error: '.$e->getMessage(), [
                'key' => $key,
                'default' => $default,
            ]);

            return $default;
        }
    }
}

if (! function_exists('settings_group')) {
    /**
     * Get all settings from a specific group
     */
    function settings_group(string $group): array
    {
        try {
            return app(SettingsService::class)->getGroup($group);
        } catch (\Exception $e) {
            Log::error('Settings group error: '.$e->getMessage());

            return [];
        }
    }
}
