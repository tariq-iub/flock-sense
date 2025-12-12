<?php

namespace App\Services;

use App\Models\Flock;
use App\Models\ProductionLog;
use App\Models\Shed;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DailyReportService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the daily report payload for a shed + date in the requested version.
     *
     * @param  string  $reportDate  // format: Y-m-d
     * @param  string  $version  // 'en' or 'ur'
     *
     * @throws NotFoundHttpException
     */
    public function build(int $shedId, string $reportDate, string $version = 'en'): array
    {
        $shed = Shed::with(['latestFlock'])->find($shedId);

        if (! $shed) {
            throw new NotFoundHttpException('Shed not found.');
        }

        $logs = ProductionLog::where('shed_id', $shedId)
            ->whereDate('production_log_date', $reportDate)
            ->with(['user', 'weightLog'])
            ->get();

        if ($logs->isEmpty()) {
            throw new NotFoundHttpException('No production data found for the specified date.');
        }

        $data = $logs->first();
        $flock = Flock::find($data->flock_id);
        if (! $flock) {
            throw new NotFoundHttpException('No active flock found for this shed.');
        }

        return $version === 'en'
            ? $this->formatEnglish($shed, $flock, $data)
            : $this->formatUrdu($shed, $flock, $data);
    }

    private function formatEnglish($shed, $flock, $data): array
    {
        return [
            'shed' => $shed->name,
            'flock' => $flock->name,
            'flock_count' => $flock->chicken_count,
            'date' => $data->production_log_date instanceof Carbon
                ? $data->production_log_date->format('d-m-Y')
                : Carbon::parse($data->production_log_date)->format('d-m-Y'),
            'age' => $data->age.' Days',
            'day_mortality_count' => $data->day_mortality_count,
            'night_mortality_count' => $data->night_mortality_count,
            '24h_mortality' => $data->total_mortality_count,
            'todate_mortality_count' => $data->todate_mortality_count,
            'net_count' => $data->net_count,
            'livability' => $data->livability.' %',
            'day_feed_consumed' => round($data->day_feed_consumed / 1000, 2).' Kg',
            'night_feed_consumed' => round($data->night_feed_consumed / 1000, 2).' Kg',
            'avg_feed_consumed' => round($data->avg_feed_consumed / 1000, 2).' Kg',
            '24h_feed_consumed' => round($data->total_feed_consumed / 1000, 2).' Kg',
            'todate_feed_consumed' => round($data->todate_feed_consumed / 1000, 2).' Kg',
            'day_water_consumed' => round($data->day_water_consumed, 2).' L',
            'night_water_consumed' => round($data->night_water_consumed, 2).' L',
            '24h_water_consumed' => round($data->total_water_consumed, 2).' L',
            'avg_water_consumed' => round($data->avg_water_consumed, 2).' L',
            'total_water_consumed' => round($data->todate_water_consumed, 2).' L',
            'is_vaccinated' => ($data->is_vaccinated ? 'Yes' : 'No'),
            'day_medicine' => $data->day_medicine ?? '',
            'night_medicine' => $data->night_medicine ?? '',
            'weighted_chickens' => optional($data->weightLog)->weighted_chickens_count ?? '',
            'recorded_weight' => $data->weightLog ? round($data->weightLog->total_weight / 1000, 2).' Kg' : '',
            'avg_weight' => $data->weightLog ? round($data->weightLog->avg_weight / 1000, 2).' Kg' : '',
            'avg_weight_gain' => $data->weightLog ? round($data->weightLog->avg_weight_gain / 1000, 2).' Kg' : '',
            'flock_weight' => $data->weightLog ? round($data->weightLog->aggregated_total_weight / 1000, 2).' Kg' : '',
            'feed_efficiency' => $data->weightLog ? round($data->weightLog->feed_efficiency, 2) : '',
            'fcr' => $data->weightLog ? round($data->weightLog->feed_conversion_ratio, 2) : '',
            'adjusted_fcr' => $data->weightLog ? round($data->weightLog->adjusted_feed_conversion_ratio, 2) : '',
            'fcr_diff' => $data->weightLog ? round($data->weightLog->fcr_standard_diff, 2) : '',
            'cv' => $data->weightLog ? round($data->weightLog->coefficient_of_variation, 2).' %' : '',
            'pef' => $data->weightLog ? round($data->weightLog->production_efficiency_factor, 2) : '',
            'submit_by' => $data->user->name,
            'submit_at' => $data->created_at instanceof Carbon
                ? $data->created_at->diffForHumans()
                : Carbon::parse($data->created_at)->diffForHumans(),
        ];
    }

    private function formatUrdu($shed, $flock, $data): array
    {
        return [
            'شیڈ' => $shed->name,
            'فلاک' => $flock->name,
            'فلاک تعداد' => $flock->chicken_count.'',
            'تاریخ' => ($data->production_log_date instanceof Carbon
                ? $data->production_log_date
                : Carbon::parse($data->production_log_date))->format('d-m-Y'),
            'عمر' => $data->age.' دن ',
            'دن اموات تعداد' => $data->day_mortality_count.'',
            'شب اموات تعداد' => $data->night_mortality_count.'',
            'کل اموات تعداد' => $data->total_mortality_count.'',
            'ابتک اموات تعداد' => $data->todate_mortality_count.'',
            'خالص تعداد' => $data->net_count.'',
            'زندہ رہنے کی شرح' => ' % '.$data->livability,
            'دن خوراک صرف شدہ' => round($data->day_feed_consumed / 1000, 2).' کلوگرام ',
            'شب خوراک صرف شدہ' => round($data->night_feed_consumed / 1000, 2).' کلوگرام ',
            'کل خوراک صرف شدہ' => round($data->total_feed_consumed / 1000, 2).' کلوگرام ',
            'اوسط خوراک صرف شدہ' => round($data->avg_feed_consumed / 1000, 2).' کلوگرام ',
            'ابتک خوراک صرف شدہ' => round($data->todate_feed_consumed / 1000, 2).' کلوگرام ',
            'دن پانی صرف شدہ' => round($data->day_water_consumed, 2).' لیٹر ',
            'شب پانی صرف شدہ' => round($data->night_water_consumed, 2).' لیٹر ',
            'کل پانی صرف شدہ' => round($data->total_water_consumed, 2).' لیٹر ',
            'اوسط پانی صرف شدہ' => round($data->avg_water_consumed, 2).' لیٹر ',
            'ویکسین شدہ' => ($data->is_vaccinated ? 'ہاں' : 'نہیں'),
            'دن دوا' => $data->day_medicine ?? '',
            'شب دوا' => $data->night_medicine ?? '',
            'وزن شدہ چکنز' => optional($data->weightLog)->weighted_chickens_count.'' ?? '',
            'ریکارڈ شدہ وزن' => $data->weightLog ? round($data->weightLog->total_weight / 1000, 2).' کلوگرام ' : '',
            'اوسط وزن' => $data->weightLog ? round($data->weightLog->avg_weight / 1000, 2).' کلوگرام ' : '',
            'اوسط وزن میں اضافہ' => $data->weightLog ? round($data->weightLog->avg_weight_gain / 1000, 2).' کلوگرام ' : '',
            'فلاک وزن' => $data->weightLog ? round($data->weightLog->aggregated_total_weight / 1000, 2).' کلوگرام ' : '',
            'خوراک کی کارکردگی' => $data->weightLog ? round($data->weightLog->feed_efficiency, 2).'' : '',
            'FCR' => $data->weightLog ? round($data->weightLog->feed_conversion_ratio, 2).'' : '',
            'FCR Diff' => $data->weightLog ? round($data->weightLog->fcr_standard_diff, 2).'' : '',
            'CV' => $data->weightLog ? round($data->weightLog->coefficient_of_variation, 2).' %' : '',
            'PEF' => $data->weightLog ? round($data->weightLog->production_efficiency_factor, 2).'' : '',
            'رپورٹر‌' => $data->user->name,
        ];
    }
}
