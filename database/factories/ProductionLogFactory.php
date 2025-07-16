<?php

namespace Database\Factories;

use App\Models\ProductionLog;
use App\Models\Flock;
use App\Models\Shed;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionLogFactory extends Factory
{
    protected $model = ProductionLog::class;

    public function definition(): array
    {
        // Always create a full chain: Flock -> Shed -> Farm -> User
        $flock = Flock::factory()->create(); // this creates Shed, Farm, User via factories
        $shed  = $flock->shed;
        $farm  = $shed->farm;
        $user  = $farm->owner;

        $chicken_count = $flock->chicken_count;
        $day_mortality = fake()->numberBetween(0, 3);
        $night_mortality = fake()->numberBetween(0, 2);
        $net_count = $chicken_count - ($day_mortality + $night_mortality);

        return [
            'shed_id' => $shed->id,
            'flock_id' => $flock->id,
            'age' => $flock->start_date->diffInDays(now()),
            'day_mortality_count' => $day_mortality,
            'night_mortality_count' => $night_mortality,
            'net_count' => $net_count,
            'livability' => round(($net_count / $chicken_count) * 100, 3),
            'day_feed_consumed' => fake()->randomFloat(2, 50, 150),
            'night_feed_consumed' => fake()->randomFloat(2, 30, 120),
            // avg_feed_consumed is computed by database, don't include it
            'day_water_consumed' => fake()->randomFloat(2, 60, 200),
            'night_water_consumed' => fake()->randomFloat(2, 40, 160),
            // avg_water_consumed is computed by database, don't include it
            'is_vaccinated' => fake()->boolean(40),
            'day_medicine' => fake()->optional()->word,
            'night_medicine' => fake()->optional()->word,
            'user_id' => $user->id,
            'created_at' => fake()->dateTimeBetween('-2 months', 'now'),
            'updated_at' => now(),
        ];
    }
}
