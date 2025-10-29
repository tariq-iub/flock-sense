<?php

namespace Database\Seeders;

use App\Models\Flock;
use App\Models\ProductionLog;
use App\Models\Shed;
use App\Services\WeightLogService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

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

        foreach ($weightRows as $index => $row) {
            // skip header row
            if ($index == 0) {
                continue;
            }

            $production_log_date = Carbon::instance(
                ExcelDate::excelToDateTimeObject($row[0])
            )->setTimezone($timezone);

            $log = ProductionLog::whereDate('production_log_date', $production_log_date->toDateString())->first();

            $service->createOrUpdateWeightLog(
                $log,                   // The found ProductionLog model
                (int) $row[1],           // weighted_chickens_count
                (float) $row[2]          // total_weight
            );
        }
    }
}
