<?php

namespace App\Imports;

use App\Models\Chart;
use App\Models\ChartData;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ChartDataImport implements ToCollection
{
    protected $chartId;

    public function __construct($chartId)
    {
        $this->chartId = $chartId;
    }

    public function collection(Collection $rows) : void
    {
        // Skip the first row if it's a header
        $firstRow = $rows->first();
        if (
            strtolower($firstRow[0]) == 'type'
            || strtolower($firstRow[1]) == 'day'
        ) {
            $rows = $rows->slice(1);
        }

        foreach ($rows as $row) {
            ChartData::create([
                'chart_id'      => $this->chartId,
                'type'          => $row[0],
                'day'           => $row[1],
                'weight'        => $row[2],
                'daily_gain'    => $row[3] ?? null,
                'avg_daily_gain'=> $row[4] ?? null,
                'daily_intake'  => $row[5] ?? null,
                'cum_intake'    => $row[6] ?? null,
                'fcr'           => $row[7] ?? null,
            ]);
        }
    }
}
