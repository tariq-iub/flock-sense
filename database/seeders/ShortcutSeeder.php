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
                'url' => '/admin/iot',
                'icon' => 'ti ti-device-desktop',
                'group' => 'admin',
                'default' => true,
            ],
            [
                'title' => 'QR Codes',
                'url' => '/admin/qr-code',
                'icon' => 'ti ti-qrcode',
                'group' => 'admin',
                'default' => true,
            ],
            [
                'title' => 'Settings',
                'url' => '/admin/system/web-settings',
                'icon' => 'ti ti-settings',
                'group' => 'admin',
                'default' => true,
            ],
            [
                'title' => 'Vaccination',
                'url' => '/admin/medicines',
                'icon' => 'ti ti-vaccine',
                'group' => 'admin',
                'default' => true,
            ],
            [
                'title' => 'Users',
                'url' => '/admin/clients',
                'icon' => 'ti ti-users',
                'group' => 'admin',
                'default' => true,
            ],
            [
                'title' => 'Roles',
                'url' => '/admin/roles',
                'icon' => 'ti ti-medal',
                'group' => 'admin',
                'default' => true,
            ],
            [
                'title' => 'User Log',
                'url' => '/admin/user/activities',
                'icon' => 'ti ti-settings-2',
                'group' => 'admin',
                'default' => true,
            ],
            [
                'title' => 'Farms',
                'url' => '/admin/farms',
                'icon' => 'ti ti-building',
                'group' => 'user',
                'default' => true,
            ],
            [
                'title' => 'Sheds',
                'url' => '/admin/sheds',
                'icon' => 'ti ti-building-warehouse',
                'group' => 'user',
                'default' => true,
            ],
            [
                'title' => 'Flocks',
                'url' => '/admin/flocks',
                'icon' => 'ti ti-feather',
                'group' => 'user',
                'default' => true,
            ],
            [
                'title' => 'Productions',
                'url' => '/admin/productions',
                'icon' => 'ti ti-chart-bar',
                'group' => 'user',
                'default' => true,
            ],
            [
                'title' => 'Staff',
                'url' => '/admin/staff',
                'icon' => 'ti ti-user-check',
                'group' => 'user',
                'default' => true,
            ],
            [
                'title' => 'Inventory',
                'url' => '/admin/inventory',
                'icon' => 'ti ti-package',
                'group' => 'user',
                'default' => true,
            ],
            [
                'title' => 'Expenses',
                'url' => '/admin/farm-expenses',
                'icon' => 'ti ti-cash',
                'group' => 'user',
                'default' => true,
            ],
            [
                'title' => 'Live Rates',
                'url' => '/admin/live-rates',
                'icon' => 'ti ti-chart-line',
                'group' => 'user', // Will handle multiple groups in code
                'default' => true,
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
