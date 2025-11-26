<?php

namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $device = Device::firstOrCreate([
            'serial_no' => 'FS18071001',
        ], [
            'model_number' => 'DTH-001',
            'manufacturer' => 'Care Lab',
            'firmware_version' => '1.0.0',
            'connectivity_type' => 'WiFi',
            'battery_operated' => false,
        ]);

        // Attach capabilities (ensure IDs exist in capabilities table)
        $device->capabilities()->syncWithoutDetaching([1, 2]);

        DB::table('shed_devices')->insertOrIgnore([
            'shed_id' => 1,
            'device_id' => $device->id,
            'is_active' => true,
            'location_in_shed' => 'Control Room',
            'link_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
