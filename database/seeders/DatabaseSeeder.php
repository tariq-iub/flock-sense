<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Muhammad Tariq',
            'email' => 'saaim01@gmail.com',
            'phone' => '+923346031107',
        ]);

        User::factory()->create([
            'name' => 'Abdullah Abid',
            'email' => 'abdrps2004@gmail.com',
            'phone' => '+923326334598',
        ]);

        $this->call([
            RoleSeeder::class,
            BreedSeeder::class,
            FeedSeeder::class,
            ProductionAndWeightLogSeeder::class,
        ]);
    }
}
