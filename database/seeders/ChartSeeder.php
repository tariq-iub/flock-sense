<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Chart;
use App\Models\ChartData; // If you store rows in a ChartData model
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class ChartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Step 1: Create the chart (modify attributes as needed)
        $chart = Chart::firstOrCreate([
            'chart_name' => 'Ross Standard',
            'source' => 'Aviagen',
        ], [
            'description' => 'Ross 308 Broiler Baseline Chart',
            'unit' => 'g',
            'settings' => null,
        ]);

        // Step 2: Load Excel rows
        $rows = Excel::toArray([], public_path('assets/data/RossStandardData.xlsx'))[0];

        // Step 3: Loop and create chart data entries
        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // skip header row

            ChartData::firstOrCreate([
                'chart_id' => $chart->id,
                'type' => $row[0] ?? 'General',
                'day' => (int) $row[1],
            ], [
                'weight' => (float) $row[2] ?? null,
                'daily_gain' => (float) $row[3] ?? null,
                'avg_daily_gain' => (float) $row[4] ?? null,
                'daily_intake' => (float) $row[5] ?? null,
                'cum_intake' => (float) $row[6] ?? null,
                'fcr' => (float) $row[3] ?? null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
