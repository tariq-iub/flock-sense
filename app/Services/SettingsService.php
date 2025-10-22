<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    public static function get($key, $default = null)
    {
        // explode by '.' to group/key
        [$group, $settingKey, $nested] = explode('.', $key, 3) + [null, null, null];
        $setting = Cache::rememberForever('settings.all', function () {
            return Setting::all()->keyBy(fn ($s) => "{$s->group}.{$s->key}");
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

    /**
     * Get all settings by group
     */
    public function getGroup(string $group): array
    {
        return Setting::where('group', $group)
            ->get()
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Set a setting value
     *
     * @param  mixed  $value
     */
    public function set(string $key, $value, ?string $group = null, string $type = 'string', ?string $description = null): bool
    {
        try {
            $parts = explode('.', $key);

            if (count($parts) === 2) {
                [$group, $settingKey] = $parts;
            } else {
                $settingKey = $key;
                $group = $group ?? 'general';
            }

            Setting::updateOrCreate(
                [
                    'group' => $group,
                    'key' => $settingKey,
                ],
                [
                    'value' => $value,
                    'type' => $type,
                    'description' => $description,
                ]
            );
            return true;

        } catch (\Exception $e) {
            Log::error('Settings set error: '.$e->getMessage());
            return false;
        }
    }
}
