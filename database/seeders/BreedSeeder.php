<?php

namespace Database\Seeders;

use App\Models\Breed;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Breed::firstOrCreate([
            'name' => 'Misri',
            'category' => 'dual-purpose',
            'origin' => 'Exotic Egyptian Breed imported in 1978 and 2016',
            'features' => 'Also know as Fayomi, Smart body and chest, single comb, less feed intake as compared to Golden',
            'weight_range' => '1.6 Kg to 2 Kg',
            'maturity_age' => '129',
            'avg_egg_production' => '178',
            'avg_egg_weight' => '45.9 g',
            'hatching_period' => '90',
            'hatchability' => '78',
        ]);

        Breed::firstOrCreate([
            'name' => 'Golden',
            'category' => 'dual-purpose',
            'origin' => 'Exotic Egyptian Breed imported in 1978 and 2016',
            'features' => 'Also know as Rhode Island Red (RIR), Smart body & chest, single comb, less feed intake as compared to RIR',
            'weight_range' => '1.6 Kg to 2 Kg',
            'maturity_age' => '129',
            'avg_egg_production' => '178',
            'avg_egg_weight' => '45.9 g',
            'hatching_period' => '90',
            'hatchability' => '78',
        ]);
    }
}
