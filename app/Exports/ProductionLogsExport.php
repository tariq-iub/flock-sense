<?php

namespace App\Exports;

use App\Models\ProductionLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductionLogsExport implements FromCollection, WithHeadings
{
    protected $logs;

    public function __construct($logs)
    {
        $this->logs = $logs;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Load relations if needed and select fields you want to export
        return $this->logs->map(function($log) {
            return [
                'created_at'  => $log->created_at->format('m-d-Y'),
                'flock'       => optional($log->flock)->name,
                'shed'        => optional($log->shed)->name,
                'age'         => $log->age,
                'net_count'   => $log->net_count,
                'livability'  => $log->livability . '%',
                'day_mortality' => $log->day_mortality_count,
                'night_mortality' => $log->night_mortality_count,
                'day_feed'    => $log->day_feed_consumed,
                'night_feed'  => $log->night_feed_consumed,
                'day_water'   => $log->day_water_consumed,
                'night_water' => $log->night_water_consumed,
                'weighted_chickens_count' => $log->weightLog->weighted_chickens_count ?? '',
                'total_weight' => $log->weightLog->total_weight ?? '',
                'avg_weight' => $log->weightLog->avg_weight ?? '',
                'avg_weight_gain' => $log->weightLog->avg_weight_gain ?? '',
                'aggregated_total_weight' => $log->weightLog->aggregated_total_weight ?? '',
                'feed_efficiency' => $log->weightLog->feed_efficiency ?? '',
                'feed_conversion_ratio' => $log->weightLog->feed_conversion_ratio ?? '',
                'adjusted_feed_conversion_ratio' => $log->weightLog->adjusted_feed_conversion_ratio ?? '',
                'fcr_standard_diff' => $log->weightLog->fcr_standard_diff ?? '',
                'standard_deviation' => $log->weightLog->standard_deviation ?? '',
                'coefficient_of_variation' => $log->weightLog->coefficient_of_variation ?? '',
                'production_efficiency_factor' => $log->weightLog->production_efficiency_factor ?? '',
                'user'        => optional($log->user)->name,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Log Date', 'Flock', 'Shed', 'Age', 'Net Count', 'Livability', 'Day Mortality', 'Night Mortality',
            'Day Feed', 'Night Feed', 'Day Water', 'Night Water', 'Weighted Chickens', 'Recorded Weight', 'Avg Weight',
            'Avg Weight Gain', 'Flock Weight', 'Feed Efficiency', 'FCR', 'Adjusted FCR', 'FCR Standard Diff',
            'SD', 'CV', 'PEF', 'User'
        ];
    }
}
