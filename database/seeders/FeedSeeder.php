<?php

namespace Database\Seeders;

use App\Models\Feed;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Feed::firstOrCreate([
            'title' => 'Starter',
            'start_day' => 0,
            'end_day' => 10,
            'feed_form' => 'Crumble',
            'particle_size' => '2.0-3.5 mm (0.08-0.14 in) diameter',
        ]);

        Feed::firstOrCreate([
            'title' => 'Grower',
            'start_day' => 11,
            'end_day' => 18,
            'feed_form' => 'Pellet',
            'particle_size' => '3.5 mm (0.12-0.02 in) diameter | 5.0-7.0 mm (0.20-0.28 in) length',
        ]);

        Feed::firstOrCreate([
            'title' => 'Finisher',
            'start_day' => 19,
            'feed_form' => 'Pellet',
            'particle_size' => '3.5 mm (0.12-0.02 in) diameter | 6.0-10.0 mm (0.24-0.40 in) length',
        ]);
    }
}
