<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Farm;
use App\Models\Shed;
use App\Models\Flock;
use App\Models\ProductionLog;
use App\Models\WeightLog;
use Illuminate\Support\Str;

class AllInOneSeeder extends Seeder
{
    public function run(): void
    {
        // Silent mode: no console output
        $faker = Faker::create('en_PK');
        $timezone = 'Asia/Karachi';
        Carbon::setLocale('en');

        // Reset (truncate) core tables to ensure reproducible run
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        WeightLog::truncate();
        ProductionLog::truncate();
        Flock::truncate();
        Shed::truncate();
        Farm::truncate();
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create 5 users
        $users = $this->createUsers(5, $faker);

        // For each user create random farms/sheds/flocks and seed logs inline
        foreach ($users as $user) {
            $farmCount = $faker->numberBetween(1, 10);

            for ($fi = 0; $fi < $farmCount; $fi++) {
                $farm = $this->createFarmForUser($user, $faker);

                $shedCount = $faker->numberBetween(1, 15);
                for ($si = 0; $si < $shedCount; $si++) {
                    $shed = $this->createShedForFarm($farm, $faker);

                    // Determine number of flocks for this shed depending on "age" randomness
                    $isOldOwner = $faker->boolean(60);
                    $flockCount = $isOldOwner ? $faker->numberBetween(10, 25) : $faker->numberBetween(1, 5);

                    // Schedule flock start/end ranges for this shed backwards so last flock ends <= today
                    $schedules = $this->scheduleFlocksForShed($flockCount, $faker);

                    // For each schedule, create the flock record and seed logs
                    foreach ($schedules as $index => $schedule) {
                        $isActive = $schedule['is_active'];
                        $startDate = $schedule['start']->copy()->setTimezone($timezone);
                        $endDate = $schedule['end'] ? $schedule['end']->copy()->setTimezone($timezone) : null;

                        $flock = Flock::create([
                            'name' => 'Flock ' . ($index + 1) . ' ' . Str::random(4),
                            'shed_id' => $shed->id,
                            'breed_id' => 1, // default single breed
                            'chicken_count' => $faker->numberBetween(5000, max(1000, $shed->capacity)),
                            'start_date' => $startDate->toDateString(),
                            'end_date' => $endDate ? $endDate->toDateString() : null,
                            'created_at' => $startDate,
                            'updated_at' => $endDate ?? now(),
                        ]);

                        // Seed production logs for this flock
                        $this->seedProductionLogsForFlock($flock, $startDate, $endDate, $faker);

                        // Seed weight logs for this flock (weekly samples)
                        $this->seedWeightLogsForFlock($flock, $faker);
                    }
                }
            }
        }

        // done (silent)
    }

    /**
     * Create n users (owners).
     * Returns collection of user models.
     */
    private function createUsers(int $n, $faker)
    {
        $users = collect();
        for ($i = 0; $i < $n; $i++) {
            $user = User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'phone' => '+92' . $faker->numerify('3#########'),
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);

            // assign owner role if spatie/permissions exists
            try {
                $user->assignRole('owner');
            } catch (\Throwable $e) {
                // ignore: role package might not be loaded in test env
            }

            $users->push($user);
        }

        return $users;
    }

    /**
     * Create a farm for a user.
     */
    private function createFarmForUser(User $user, $faker): Farm
    {
        return Farm::create([
            'name' => ucfirst($faker->word()) . ' Farm',
            'province_id' => null,
            'district_id' => null,
            'city_id' => null,
            'address' => $faker->address(),
            'owner_id' => $user->id,
            'latitude' => $faker->latitude(),
            'longitude' => $faker->longitude(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Create a shed for a farm.
     */
    private function createShedForFarm(Farm $farm, $faker): Shed
    {
        return Shed::create([
            'farm_id' => $farm->id,
            'name' => 'Shed ' . ($faker->numberBetween(1, 99)),
            'capacity' => $faker->numberBetween(5000, 30000),
            'type' => $faker->randomElement(['default', 'broiler', 'layer', 'brooder']),
            'description' => $faker->sentence(6),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Build backward schedules (start/end) for $count flocks in a shed so that last flock ends <= today.
     * Returns array of schedules oldest-first (index 0 oldest, last index latest/active).
     * Each schedule = ['start' => Carbon, 'end' => Carbon|null, 'is_active' => bool]
     */
    private function scheduleFlocksForShed(int $flockCount, $faker): array
    {
        $today = Carbon::today('Asia/Karachi')->copy();
        // Let the last flock (most recent) end a few days before today or today
        $lastEnd = $today->copy()->subDays($faker->numberBetween(0, 3));

        $schedules = [];

        // Build backwards: last -> first
        for ($i = $flockCount - 1; $i >= 0; $i--) {
            $duration = $faker->numberBetween(40, 50); // lifecycle days
            $start = $lastEnd->copy()->subDays($duration);
            $end = $lastEnd->copy();

            $isActive = ($i === $flockCount - 1) ? $faker->boolean(70) : false;
            // if active, we set end null and we will create partial logs later (end will be null)
            if ($isActive) {
                // For active flock, pick a start within last ~50 days so partial logs are reasonable.
                // Optionally push start more recent than computed to produce partial cycle
                $recentStartShift = $faker->numberBetween(0, intval(max(0, $duration - 10)));
                $start = $end->copy()->subDays($duration - $recentStartShift);
                $end = null;
            }

            $schedules[] = [
                'start' => $start->copy(),
                'end' => $end ? $end->copy() : null,
                'is_active' => (bool)$isActive,
            ];

            // Move pointer backwards to before this flock (gap)
            $gap = $faker->numberBetween(10, 15);
            $lastEnd = $start->copy()->subDays($gap);
        }

        // schedules currently newest-first (because we iterated backwards). Reverse to oldest-first.
        return array_reverse($schedules);
    }

    /**
     * Seed daily production logs for a flock.
     * For completed flocks (end date present): create full daily logs between start and end.
     * For active flocks (end date null): create partial logs between start and either today or a partial random length (10-40 days).
     */
    private function seedProductionLogsForFlock(Flock $flock, Carbon $startDate, ?Carbon $endDate, $faker): void
    {
        // Parameters and helpers
        $variation = 0.10;
        $day_split = 0.65;
        $night_split = 0.35;
        $hot_season_multiplier = 1.0;

        $randVariation = function ($base) use ($variation, $faker) {
            return $base * (1 + $faker->randomFloat(4, -$variation, $variation));
        };

        // simple per-age feed and water functions (g per bird, ml per bird)
        $feedGPerBird = function ($age) {
            return 6.0 * max(1, $age) + 10.0;
        };
        $waterMlPerBird = function ($age) {
            return 5.28 * max(1, $age);
        };

        $startingCount = $flock->chicken_count > 0 ? $flock->chicken_count : 1000;
        $lastNetCount = $startingCount;

        $today = Carbon::today('Asia/Karachi');

        // Determine the sequence length
        if ($endDate) {
            $days = $startDate->diffInDays($endDate);
        } else {
            // active flock: partial logs - random 10..min(40, days since start)
            $maxPossible = max(1, $startDate->diffInDays($today));
            $days = $faker->numberBetween(10, min(40, max(10, $maxPossible)));
        }

        for ($d = 0; $d <= $days; $d++) {
            $productionDate = (clone $startDate)->addDays($d);
            // do not seed future dates
            if ($productionDate->greaterThan($today)) break;

            $age = $d;

            $feed_g = $randVariation($feedGPerBird($age));
            $water_ml = $randVariation($waterMlPerBird($age));

            $total_feed_g = max(0, $feed_g * $lastNetCount);
            $day_feed = round($total_feed_g * $day_split, 2);
            $night_feed = round($total_feed_g * $night_split, 2);

            $total_water_l = ($water_ml / 1000.0) * $lastNetCount * $hot_season_multiplier;
            $day_water = round($total_water_l * $day_split, 2);
            $night_water = round($total_water_l * $night_split, 2);

            // mortality simulation: small chance & poisson-like average deaths
            $dayMort = 0;
            $nightMort = 0;
            $base_frac = $age <= 3 ? 0.003 : ($age <= 10 ? 0.0015 : 0.0009);
            $expectedDeaths = $lastNetCount * $base_frac;
            if ($faker->randomFloat(0, 1) < 0.02) {
                $expectedDeaths *= (1 + $faker->randomFloat(2, 1.0, 3.0));
            }
            // sample deaths (rounded)
            $totalDeaths = (int)round($this->poissonLike($expectedDeaths));
            $dayShare = 0.6 + $faker->randomFloat(2, -0.1, 0.1);
            $dayMort = (int)round($totalDeaths * $dayShare);
            $nightMort = max(0, $totalDeaths - $dayMort);

            $net_count = max(0, $lastNetCount - ($dayMort + $nightMort));
            $livability = $startingCount > 0 ? round(($net_count / $startingCount) * 100, 3) : 0;

            // Avoid duplicates: check by flock & date
            $exists = ProductionLog::where('flock_id', $flock->id)
                ->whereDate('production_log_date', $productionDate->toDateString())
                ->exists();
            if (!$exists) {
                ProductionLog::create([
                    'shed_id' => $flock->shed_id,
                    'flock_id' => $flock->id,
                    'production_log_date' => $productionDate,
                    'age' => $age,
                    'day_mortality_count' => $dayMort,
                    'night_mortality_count' => $nightMort,
                    'net_count' => $net_count,
                    'livability' => $livability,
                    'day_feed_consumed' => $day_feed,
                    'night_feed_consumed' => $night_feed,
                    'day_water_consumed' => $day_water,
                    'night_water_consumed' => $night_water,
                    'is_vaccinated' => ($d % 7 === 0) || $faker->boolean(8),
                    'day_medicine' => $faker->boolean(20) ? $faker->word() : null,
                    'night_medicine' => $faker->boolean(5) ? $faker->word() : null,
                    'user_id' => $flock->shed->farm->owner_id ?? 1,
                    'created_at' => $productionDate,
                    'updated_at' => $productionDate,
                ]);
            }

            $lastNetCount = $net_count;
            if ($lastNetCount <= 0) break;
        }

        // done seeding production logs for this flock
    }

    /**
     * Seed weekly weight logs for a flock, linked to production logs.
     * Weekly sampling: every 7th day (age % 7 == 0), ages >= 7
     * Computes avg_weight, total_weight, avg_weight_gain, aggregated_total_weight,
     * feed_conversion_ratio, adjusted_fcr, fcr_standard_diff, standard_deviation, coefficient_of_variation, pef.
     */
    private function seedWeightLogsForFlock(Flock $flock, $faker): void
    {
        $variation = 0.10;
        $sampleInterval = 7;

        // growth anchors in grams
        $anchors = [
            7 => 150,
            14 => 400,
            21 => 900,
            28 => 1600,
            35 => 2400,
            42 => 3000,
            49 => 3300,
        ];

        $expectedAvgWeightG = function ($age) use ($anchors) {
            $ages = array_keys($anchors);
            sort($ages);
            if ($age <= $ages[0]) return $anchors[$ages[0]];
            if ($age >= end($ages)) return $anchors[end($ages)];
            for ($i = 0; $i < count($ages) - 1; $i++) {
                $a = $ages[$i];
                $b = $ages[$i + 1];
                if ($age >= $a && $age <= $b) {
                    $wa = $anchors[$a];
                    $wb = $anchors[$b];
                    $t = ($age - $a) / ($b - $a);
                    return $wa + ($wb - $wa) * $t;
                }
            }
            return $anchors[$ages[0]];
        };

        $prodLogs = ProductionLog::where('flock_id', $flock->id)
            ->orderBy('production_log_date')
            ->get();

        if ($prodLogs->isEmpty()) return;

        $lastTotalWeightKg = null;
        $lastSampleDate = null;

        // Collect previous avg_weight values to compute stddev when needed
        $previousAvgWeights = [];

        foreach ($prodLogs as $pl) {
            $age = (int)$pl->age;
            if ($age < 7) continue;
            if ($age % $sampleInterval !== 0) continue;

            // skip if weight log already exists for that production_log
            if (WeightLog::where('production_log_id', $pl->id)->exists()) {
                // update trackers from existing if needed
                $existing = WeightLog::where('production_log_id', $pl->id)->first();
                if ($existing) {
                    $lastTotalWeightKg = ($existing->total_weight ?? 0) / 1000.0;
                    $lastSampleDate = $existing->created_at;
                    $previousAvgWeights[] = $existing->avg_weight ?? 0;
                }
                continue;
            }

            $weightedCount = ($pl->net_count && $pl->net_count > 0) ? (int)$pl->net_count : (int)$flock->chicken_count;
            if ($weightedCount <= 0) $weightedCount = max(100, (int)$flock->chicken_count);

            // compute average weight with jitter
            $avgWeightG = $expectedAvgWeightG($age) * (1 + $faker->randomFloat(4, -$variation, $variation));
            $avgWeightG = round($avgWeightG, 3);

            $totalWeightG = round($avgWeightG * $weightedCount, 3);

            // average weight gain per day since last sample
            $avgWeightGainG = 0.0;
            if ($lastTotalWeightKg !== null) {
                $gain_g = ($totalWeightG - ($lastTotalWeightKg * 1000.0)) / max(1, $sampleInterval);
                $avgWeightGainG = round($gain_g, 3);
            }

            // period feed total between last sample and this pl date
            if ($lastSampleDate) {
                $feedRows = ProductionLog::where('flock_id', $flock->id)
                    ->where('production_log_date', '>', $lastSampleDate)
                    ->where('production_log_date', '<=', $pl->production_log_date)
                    ->get();
            } else {
                $feedRows = ProductionLog::where('flock_id', $flock->id)
                    ->where('production_log_date', '<=', $pl->production_log_date)
                    ->get();
            }

            $periodFeedG = 0.0;
            foreach ($feedRows as $fr) {
                $periodFeedG += (float)$fr->day_feed_consumed + (float)$fr->night_feed_consumed;
            }

            $weightGainKgPeriod = 0.0;
            if ($lastTotalWeightKg !== null) {
                $weightGainKgPeriod = ($totalWeightG / 1000.0) - $lastTotalWeightKg;
            }

            $fcr = null;
            if ($weightGainKgPeriod > 0) {
                $fcr = ($periodFeedG / 1000.0) / $weightGainKgPeriod;
                $fcr = round($fcr * (1 + $faker->randomFloat(4, -0.03, 0.03)), 3);
            }

            // adjusted FCR and fcr_standard_diff: attempt to use ChartData model if available, fallback otherwise
            $expectedWeight = $avgWeightG;
            $adjustedFcr = null;
            $fcrStandardDiff = null;

            // if ChartData model exists and has relevant record, try to use it (optional)
            try {
                if (class_exists('\App\Models\ChartData')) {
                    $chart = \App\Models\ChartData::where('day', $pl->age)->first();
                    if ($chart) {
                        if (isset($chart->weight)) $expectedWeight = $chart->weight;
                        if ($fcr !== null && isset($chart->fcr)) {
                            $fcrStandardDiff = round($chart->fcr - $fcr, 3);
                        }
                    }
                }
            } catch (\Throwable $e) {
                // ignore chart fetch issues
            }

            if ($fcr !== null) {
                // small adjustment proportional to difference between expected weight and actual
                $adjustedFcr = round($fcr + (($expectedWeight - $avgWeightG) / 4500.0), 3);
            }

            if ($fcrStandardDiff === null && $fcr !== null) {
                // fallback estimate for standard FCR by age-weeks
                $ageWeeks = max(1, intval($age / 7));
                $standardFcrEstimate = 1.3 + 0.02 * $ageWeeks;
                $fcrStandardDiff = round($standardFcrEstimate - $fcr, 3);
            }

            // compute standard deviation and coefficient of variation from previous weight samples
            $previousWeights = collect($previousAvgWeights)->filter()->values();
            $stddev = 0.0;
            if ($previousWeights->count() > 0) {
                $mean = $previousWeights->avg();
                $variance = $previousWeights->reduce(function ($carry, $val) use ($mean) {
                        return $carry + pow($val - $mean, 2);
                    }, 0) / $previousWeights->count();
                $stddev = round(sqrt($variance), 3);
            }
            $coefficientOfVariation = $avgWeightG > 0 ? round(($stddev / $avgWeightG) * 100, 3) : 0.0;

            // production efficiency factor (PEF) = livability * (aggregated_total_weight / 1000) / (age * fcr)
            $livability = $pl->livability ?? 100.0;
            $productionEfficiencyFactor = 0.0;
            if ($pl->age > 0 && $fcr && $fcr > 0) {
                $productionEfficiencyFactor = round($livability * ($totalWeightG / 1000.0) / ($pl->age * $fcr), 3);
            }

            // feed efficiency fallback
            $feedEfficiency = null;
            if ($periodFeedG > 0) {
                $feedEfficiency = round(($totalWeightG / ($periodFeedG ?: 1)), 3);
            }

            if ($fcr === null) {
                continue;
            }

            WeightLog::create([
                'production_log_id' => $pl->id,
                'flock_id' => $flock->id,
                'weighted_chickens_count' => $weightedCount,
                'total_weight' => round($totalWeightG, 3),
                'avg_weight' => round($avgWeightG, 3),
                'avg_weight_gain' => round($avgWeightGainG, 3),
                'aggregated_total_weight' => round($totalWeightG, 3),
                'feed_efficiency' => $feedEfficiency,
                'feed_conversion_ratio' => $fcr,
                'adjusted_feed_conversion_ratio' => $adjustedFcr,
                'fcr_standard_diff' => $fcrStandardDiff,
                'standard_deviation' => $stddev,
                'coefficient_of_variation' => $coefficientOfVariation,
                'production_efficiency_factor' => $productionEfficiencyFactor,
                'created_at' => $pl->production_log_date,
                'updated_at' => $pl->production_log_date,
            ]);

            // update trackers for next sample
            $lastTotalWeightKg = $totalWeightG / 1000.0;
            $lastSampleDate = $pl->production_log_date;
            $previousAvgWeights[] = $avgWeightG;
        }
    }

    private function poissonLike($mean)
    {
        // Simple approximate Poisson random generator
        // Just adds small noise around mean (±sqrt(mean))
        $variance = sqrt($mean);
        return max(1, (int)round($mean + random_int(-$variance, $variance)));
    }
}
