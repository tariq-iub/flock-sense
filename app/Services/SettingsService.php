<?php

namespace App\Services;

class SettingsService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function get($key, $default = null)
    {
        // explode by '.' to group/key
        [$group, $settingKey, $nested] = explode('.', $key, 3) + [null, null, null];
        $setting = Cache::rememberForever('settings.all', function () {
            return \App\Models\Setting::all()->keyBy(fn($s) => "{$s->group}.{$s->key}");
        });
        $value = $setting["$group.$settingKey"]->value ?? $default;

        // Decrypt if needed
        if ($setting["$group.$settingKey"]->is_encrypted ?? false) {
            $value = decrypt($value);
        }
        // Decode JSON if type is json
        if ($setting["$group.$settingKey"]->type === 'json') {
            $value = json_decode($value, true);
            // If nested key requested
            if (isset($nested[0])) {
                foreach (explode('.', $nested[0]) as $segment) {
                    $value = $value[$segment] ?? null;
                }
            }
        }
        return $value;
    }
}
