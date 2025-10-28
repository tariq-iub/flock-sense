<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShortcutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shortcuts = [
            [
                'title' => 'Devices',
                'url' => '/devices',
                'icon' => 'ti ti-device-desktop',
                'group' => 'admin',
                'default' => true,
            ],
            [
                'title' => 'QR Printing',
                'url' => '/qr-printing',
                'icon' => 'ti ti-qrcode',
                'group' => 'admin',
                'default' => true,
            ],
            [
                'title' => 'Settings',
                'url' => '/settings',
                'icon' => 'ti ti-settings',
                'group' => 'admin',
                'default' => true,
            ],
            [
                'title' => 'Vaccination',
                'url' => '/vaccination',
                'icon' => 'ti ti-vaccine',
                'group' => 'admin',
                'default' => false,
            ],
            [
                'title' => 'Farms',
                'url' => '/farms',
                'icon' => 'ti ti-building-farm',
                'group' => 'admin', // Will handle multiple groups in code
                'default' => false,
            ],
            [
                'title' => 'Sheds',
                'url' => '/sheds',
                'icon' => 'ti ti-building-warehouse',
                'group' => 'admin', // Will handle multiple groups in code
                'default' => false,
            ],
            [
                'title' => 'Flocks',
                'url' => '/flocks',
                'icon' => 'ti ti-feather',
                'group' => 'admin', // Will handle multiple groups in code
                'default' => false,
            ],
            [
                'title' => 'Users',
                'url' => '/users',
                'icon' => 'ti ti-users',
                'group' => 'admin',
                'default' => true,
            ],
            [
                'title' => 'Staff',
                'url' => '/staff',
                'icon' => 'ti ti-user-check',
                'group' => 'user',
                'default' => true,
            ],
            [
                'title' => 'Analytics',
                'url' => '/analytics',
                'icon' => 'ti ti-chart-bar',
                'group' => 'admin', // Will handle multiple groups in code
                'default' => true,
            ],
            [
                'title' => 'Inventory',
                'url' => '/inventory',
                'icon' => 'ti ti-package',
                'group' => 'user',
                'default' => false,
            ],
            [
                'title' => 'Expenses',
                'url' => '/expenses',
                'icon' => 'ti ti-cash',
                'group' => 'user',
                'default' => false,
            ],
            [
                'title' => 'Live Rates',
                'url' => '/live-rates',
                'icon' => 'ti ti-chart-line',
                'group' => 'admin', // Will handle multiple groups in code
                'default' => false,
            ],
        ];

        foreach ($shortcuts as $shortcut) {
            DB::table('shortcuts')->insert([
                'title' => $shortcut['title'],
                'url' => $shortcut['url'],
                'icon' => $shortcut['icon'],
                'group' => $shortcut['group'],
                'default' => $shortcut['default'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create additional entries for shortcuts that belong to multiple groups
        $multiGroupShortcuts = [
            'Farms' => 'user',
            'Sheds' => 'user',
            'Flocks' => 'user',
            'Analytics' => 'user',
            'Live Rates' => 'user',
        ];

        foreach ($multiGroupShortcuts as $title => $group) {
            $existing = DB::table('shortcuts')
                ->where('title', $title)
                ->where('group', $group)
                ->first();

            if (! $existing) {
                $original = DB::table('shortcuts')
                    ->where('title', $title)
                    ->first();

                DB::table('shortcuts')->insert([
                    'title' => $title,
                    'url' => $original->url,
                    'icon' => $original->icon,
                    'group' => $group,
                    'default' => $original->default,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Get all admin shortcuts
        $adminShortcuts = DB::table('shortcuts')
            ->where('group', 'admin')
            ->pluck('id')
            ->toArray();

        // Prepare data for user_id 1 (admin)
        $shortcutUserData = [];

        foreach ($adminShortcuts as $shortcutId) {
            $shortcutUserData[] = [
                'user_id' => 1,
                'shortcut_id' => $shortcutId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert the data
        DB::table('shortcut_user')
            ->insert($shortcutUserData);
    }
}
