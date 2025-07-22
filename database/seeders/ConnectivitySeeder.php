<?php

namespace Database\Seeders;

use App\Models\Connectivity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConnectivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ['WiFi', 'LoRa', 'Zigbee', 'Ethernet', 'GSM'];
        foreach ($data as $d) {
            Connectivity::firstOrCreate([
                'name' => $d,
                'is_active' => true,
            ]);
        }
    }
}
