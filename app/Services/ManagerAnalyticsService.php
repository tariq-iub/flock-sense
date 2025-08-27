<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ManagerAnalyticsService
{
    public function getAnalyticsData(array $filters = []): array
    {
        $start_date = $filters['start_date'] ?: now()->subDays(30)->toDateString();
        $end_date = $filters['end_date'] ?: now()->toDateString();

        $sql = "WITH active_flocks AS (
                  SELECT f.id, f.shed_id, f.chicken_count, f.start_date, f.end_date
                  FROM flocks f
                  JOIN sheds s ON s.id = f.shed_id
                  WHERE s.farm_id = {$filters['farm_id']}
                    AND f.start_date <= CURRENT_DATE()
                    AND (f.end_date IS NULL OR f.end_date >= CURRENT_DATE())
                ),
                latest_pl AS (
                  -- Latest production log per active flock within window (falls back to latest overall)
                  SELECT pl.*
                  FROM production_logs pl
                  JOIN (
                    SELECT flock_id, MAX(production_log_date) AS max_d
                    FROM production_logs
                    WHERE production_log_date BETWEEN '{$start_date}' AND '{$end_date}'
                    GROUP BY flock_id
                  ) x ON x.flock_id = pl.flock_id AND x.max_d = pl.production_log_date
                ),
                fallback_pl AS (
                  -- If a flock has no log in the window, take its latest ever
                  SELECT pl.*
                  FROM production_logs pl
                  JOIN (
                    SELECT flock_id, MAX(production_log_date) AS max_d
                    FROM production_logs
                    GROUP BY flock_id
                  ) x ON x.flock_id = pl.flock_id AND x.max_d = pl.production_log_date
                ),
                chosen_pl AS (
                  -- Prefer latest within window; otherwise fallback latest
                  SELECT af.id AS flock_id,
                         COALESCE(lp.net_count, fp.net_count)           AS net_count,
                         COALESCE(lp.livability, fp.livability)         AS livability,
                         COALESCE(lp.day_mortality_count,0)+COALESCE(lp.night_mortality_count,0) AS win_mortality,
                         COALESCE(fp.day_mortality_count,0)+COALESCE(fp.night_mortality_count,0) AS fb_mortality
                  FROM active_flocks af
                  LEFT JOIN latest_pl lp   ON lp.flock_id = af.id
                  LEFT JOIN fallback_pl fp ON fp.flock_id = af.id
                ),
                farm_window AS (
                  -- Window aggregate for the farm in the period
                  SELECT
                    SUM(pl.day_mortality_count + pl.night_mortality_count) AS period_mortalities,
                    SUM(pl.day_feed_consumed + pl.night_feed_consumed)/1000     AS period_feed_kg,
                    SUM(pl.day_water_consumed + pl.night_water_consumed)   AS period_water_l
                  FROM production_logs pl
                  JOIN flocks f   ON f.id = pl.flock_id
                  JOIN sheds  s   ON s.id = f.shed_id
                  WHERE s.farm_id = {$filters['farm_id']}
                    AND pl.production_log_date BETWEEN '{$start_date}' AND '{$end_date}'
                ),
                fcr_pef AS (
                  -- FCR/PEF via weight_logs (latest per flock within window)
                  SELECT
                    AVG(wl.feed_conversion_ratio)                  AS avg_fcr,
                    AVG(wl.adjusted_feed_conversion_ratio)         AS avg_adj_fcr,
                    AVG(wl.production_efficiency_factor)           AS avg_pef
                  FROM weight_logs wl
                  JOIN production_logs pl ON pl.id = wl.production_log_id
                  JOIN flocks f ON f.id = wl.flock_id
                  JOIN sheds  s ON s.id = f.shed_id
                  WHERE s.farm_id = {$filters['farm_id']}
                    AND pl.production_log_date BETWEEN '{$start_date}' AND '{$end_date}'
                )
                SELECT
                  (SELECT COUNT(*) FROM active_flocks)                             AS active_flocks,
                  COALESCE(SUM(ch.net_count), 0)                                   AS total_birds_current,
                  -- Mortality rate over window relative to (sum of latest net_count or stocked birds if you prefer)
                  COALESCE((SELECT period_mortalities FROM farm_window),0)         AS total_mortalities_window,
                  CASE
                    WHEN COALESCE(SUM(ch.net_count),0) > 0
                    THEN ROUND((SELECT period_mortalities FROM farm_window) * 100.0 / SUM(ch.net_count), 2)
                    ELSE 0
                  END                                                              AS mortality_rate_pct,
                  ROUND(AVG(ch.livability),2)                                      AS avg_livability_pct,
                  ROUND(COALESCE((SELECT avg_fcr FROM fcr_pef),0), 3)              AS avg_fcr,
                  ROUND(COALESCE((SELECT avg_adj_fcr FROM fcr_pef),0), 3)          AS avg_adj_fcr,
                  ROUND(COALESCE((SELECT avg_pef FROM fcr_pef),0), 1)              AS avg_pef,
                  ROUND(COALESCE((SELECT period_feed_kg FROM farm_window),0), 2)   AS feed_kg_window,
                  ROUND(COALESCE((SELECT period_water_l FROM farm_window),0), 2)   AS water_l_window
                FROM chosen_pl ch";

        return DB::select($sql);
    }

    public function getMortalityRateData(array $filters = []): array
    {
        $start_date = $filters['start_date'] ?: now()->subDays(30)->toDateString();
        $end_date = $filters['end_date'] ?: now()->toDateString();

        $sql = "WITH plw AS (
                  SELECT
                    pl.flock_id,
                    f.NAME AS flock_name,
                    s.name AS shed_name,
                    DATE(pl.production_log_date) AS d,
                    pl.age,
                    (pl.day_mortality_count + pl.night_mortality_count) AS deaths,
                    pl.net_count AS end_count
                  FROM
                    production_logs pl
                    JOIN flocks f ON f.id = pl.flock_id
                    JOIN sheds s ON s.id = f.shed_id
                  WHERE
                    pl.production_log_date BETWEEN '{$start_date}'
                    AND '{$end_date}'
                    AND s.farm_id = {$filters['farm_id']}
                ),
                x AS (
                  SELECT
                    plw.*,
                    LAG(plw.end_count) OVER (PARTITION BY plw.flock_id ORDER BY plw.d) AS prev_end
                  FROM
                    plw
                ) SELECT
                  x.flock_id,
                  x.flock_name,
                  x.shed_name,
                  x.d,
                  x.age,
                  CASE
                    WHEN COALESCE(x.prev_end, x.end_count + x.deaths) = 0 THEN
                      0
                    ELSE
                      (x.deaths * 1.0) / COALESCE(x.prev_end, x.end_count + x.deaths)
                  END AS mortality_rate
                FROM
                  x
                ORDER BY
                  x.flock_id,
                  x.d";

        $rows = DB::select($sql);

        $seriesByFlock = [];
        foreach ($rows as $r) {
            $key = $r->flock_id;
            if (! isset($seriesByFlock[$key])) {
                $seriesByFlock[$key] = [
                    'label' => "{$r->flock_name} ({$r->shed_name})",
                    'data' => [],
                ];
            }
            // Chart.js expects y as number; convert to percentage later in options
            $seriesByFlock[$key]['data'][] = [
                'x' => $r->age,                                // YYYY-MM-DD
                'y' => round(((float) $r->mortality_rate) * 100, 4),  // % for nicer tooltips
            ];
        }

        return array_values($seriesByFlock);
    }

    public function adgData(array $filters = []): array
    {
        $start_date = $filters['start_date'] ?: now()->subDays(30)->toDateString();
        $end_date = $filters['end_date'] ?: now()->toDateString();

        $sql = "SELECT
                  w.flock_id,
                  f.name AS flock_name,
                  s.name AS shed_name,
                  DATE(p.production_log_date) AS d,
                  p.age,
                  AVG(w.avg_weight_gain) AS adg_g_per_bird
                FROM weight_logs w
                JOIN production_logs p ON p.id = w.production_log_id
                JOIN flocks f          ON f.id = w.flock_id
                JOIN sheds  s          ON s.id = f.shed_id
                WHERE p.production_log_date BETWEEN '{$start_date}' AND '{$end_date}'
                AND s.farm_id = {$filters['farm_id']}
                GROUP BY w.flock_id, f.name, DATE(p.production_log_date), p.age
                ORDER BY w.flock_id, p.age";

        $rows = DB::select($sql);

        // ---- Build labels (ages) and datasets (one per flock) ----
        $ageSet = [];
        $flockSeries = [];  // [flock_id => ['label'=>..., 'points'=>[age => value]]]

        foreach ($rows as $r) {
            $age = (int) $r->age;
            $ageSet[$age] = true;

            if (!isset($flockSeries[$r->flock_id])) {
                $flockSeries[$r->flock_id] = [
                    'label'  => "{$r->flock_name} ({$r->shed_name})",
                    'points' => [],
                ];
            }
            // store the ADG (grams per bird) for this age
            $flockSeries[$r->flock_id]['points'][$age] = round((float) $r->adg_g_per_bird, 0);
        }

        // Sorted unique ages as x-axis labels
        $labels = array_keys($ageSet);
        sort($labels, SORT_NUMERIC);

        // Align each dataset’s data array to the labels (null where missing)
        $datasets = [];
        foreach ($flockSeries as $series) {
            $data = [];
            foreach ($labels as $age) {
                $data[] = $series['points'][$age] ?? null;
            }
            $datasets[] = [
                'label' => $series['label'],
                'data'  => $data,  // aligned to $labels by index
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets
        ];
    }

    public function environmentData(array $filters = []): array
    {
        $start_date = $filters['start_date'] ?: now()->subDays(30)->toDateString();
        $end_date = $filters['end_date'] ?: now()->toDateString();
        $co2_limit = 3000;
        $nh3_limit = 25;

        $sql = "WITH last_param AS (
                  SELECT l.*
                  FROM iot_data_logs l
                  JOIN (
                    SELECT shed_id, parameter, MAX(record_time) AS mx
                    FROM iot_data_logs
                    GROUP BY shed_id, parameter
                  ) x ON x.shed_id = l.shed_id AND x.parameter = l.parameter AND x.mx = l.record_time
                )
                SELECT
                  s.id AS shed_id,
                  s.name AS shed_name,
                  MAX(CASE WHEN lp.parameter='temp1' THEN lp.avg_value END) AS shed_temperature_c,
                  MAX(CASE WHEN lp.parameter='temp2' THEN lp.avg_value END) AS brooder_temperature_c,
                  MAX(CASE WHEN lp.parameter='humidity'    THEN lp.avg_value END) AS humidity_pct,
                  MAX(CASE WHEN lp.parameter='co2'         THEN lp.avg_value END) AS co2_ppm,
                  MAX(CASE WHEN lp.parameter='nh3'         THEN lp.avg_value END) AS nh3_ppm,
                  MAX(lp.record_time) AS last_reading,
                  CASE
                    -- WHEN MAX(CASE WHEN lp.parameter='temp1' THEN lp.avg_value END) IS NULL THEN 'NO DATA'
                    -- WHEN MAX(CASE WHEN lp.parameter='temp2' THEN lp.avg_value END) IS NULL THEN 'NO DATA'
                    WHEN MAX(CASE WHEN lp.parameter='temp1' THEN lp.avg_value END) NOT BETWEEN 20 AND 25
                      OR MAX(CASE WHEN lp.parameter='temp2' THEN lp.avg_value END) NOT BETWEEN 29 AND 33
                      OR MAX(CASE WHEN lp.parameter='humidity'    THEN lp.avg_value END) NOT BETWEEN 0.6 AND 0.7
                      OR COALESCE(MAX(CASE WHEN lp.parameter='co2' THEN lp.avg_value END),0) > $co2_limit
                      OR COALESCE(MAX(CASE WHEN lp.parameter='nh3' THEN lp.avg_value END),0) > $nh3_limit
                    THEN 'ALERT' ELSE 'OK'
                  END AS status
                FROM sheds s
                LEFT JOIN last_param lp ON lp.shed_id = s.id
                WHERE s.farm_id = {$filters['farm_id']}
                GROUP BY s.id, s.name
                ORDER BY s.name";

        return DB::select($sql);
    }
}
