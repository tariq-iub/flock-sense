<?php

namespace Database\Seeders;

use App\Models\Flock;
use App\Models\Shed;
use App\Services\WeightLogService;
use App\Models\ProductionLog;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class WeightLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $service = app(WeightLogService::class);
        $weightRows = Excel::toArray([], public_path('assets/data/WeightData.xlsx'))[0];
        $timezone = 'Asia/Karachi';

        $shed_ids = Shed::all()->pluck('id')->toArray();
        $flock_ids = Flock::all()->pluck('id')->toArray();

        foreach ($weightRows as $index => $row) {
            if ($index == 0) continue; // skip header row

            // 1. Parse date (assume $row[0] is Excel date, $row[1] weighted_chickens_count, $row[2] total_weight)
            $production_log_date = Carbon::instance(
                ExcelDate::excelToDateTimeObject($row[0])
            )->setTimezone($timezone);

            // 2. Get related flock and opening count
            $flock_id = (int)$row[2];
            $shed_id = (int)$row[3];

            $flock = Flock::find($flock_id);
            $shed = Shed::find($shed_id);

            if (!$shed or !$flock) continue;

            // 3. Find the related Production Log (by date, flock, or shed as needed)
            $log = ProductionLog::whereDate('production_log_date', $production_log_date->toDateString())->first();

            $service->createOrUpdateWeightLog(
                $log,                   // The found ProductionLog model
                (int)$row[1],           // weighted_chickens_count
                (float)$row[2]          // total_weight
            );
        }
    }
}
