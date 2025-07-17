<?php

namespace Database\Factories;

use App\Models\WeightLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WeightLog>
 */
class WeightLogFactory extends Factory
{
    protected $model = WeightLog::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // We'll let seeder pass production_log_id and correct values for associations
        $weighted_chickens_count = fake()->numberBetween(10, 100);
        $avg_weight = fake()->randomFloat(3, 1.2, 2.6); // e.g., kg
        $total_weight = $weighted_chickens_count * $avg_weight;

        return [
            // 'production_log_id' => to be set in seeder
            'weighted_chickens_count' => $weighted_chickens_count,
            'total_weight' => $total_weight,
            'avg_weight' => $avg_weight,
            'avg_weight_gain' => fake()->randomFloat(3, 0.01, 0.2),
            'aggregated_total_weight' => fake()->randomFloat(3, 500, 3000),
            'feed_efficiency' => fake()->randomFloat(3, 1.0, 2.5),
            'feed_conversion_ratio' => fake()->randomFloat(3, 1.3, 2.3),
            'adjusted_feed_conversion_ratio' => fake()->randomFloat(3, 1.3, 2.3),
            'fcr_standard_diff' => fake()->randomFloat(3, -0.2, 0.2),
            'standard_deviation' => fake()->randomFloat(3, 0, 0.5),
            'coefficient_of_variation' => fake()->randomFloat(3, 0, 30),
            'production_efficiency_factor' => fake()->randomFloat(3, 100, 400),
            'created_at' => fake()->dateTimeBetween('-2 months', 'now'),
            'updated_at' => now(),
        ];
    }
}
