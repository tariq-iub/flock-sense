<?php

namespace Database\Factories;

use App\Models\Breed;
use App\Models\Flock;
use App\Models\Shed;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flock>
 */
class FlockFactory extends Factory
{
    protected $model = Flock::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $chicken_count = fake()->numberBetween(1000, 5000);
        return [
            'shed_id' => Shed::factory(),
            'breed_id' => 1,
            'name' => 'Flock ' . fake()->unique()->numberBetween(1, 999),
            'start_date' => Carbon::now()->subDays(fake()->numberBetween(1, 30)),
            'chicken_count' => $chicken_count,
        ];
    }
}
