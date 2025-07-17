<?php

namespace Database\Factories;

use App\Models\Farm;
use App\Models\Shed;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shed>
 */
class ShedFactory extends Factory
{
    protected $model = Shed::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'farm_id' => Farm::factory(),
            'name' => 'Shed ' . fake()->unique()->numberBetween(1, 99),
            'capacity' => 20000,
        ];
    }
}
