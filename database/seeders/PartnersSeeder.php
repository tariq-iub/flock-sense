<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partners = [
            [
                'company_name' => 'Cobb-Vantress',
                'url' => 'https://www.cobb-vantress.com',
                'introduction' => 'Cobb-Vantress is a leading global poultry breeding company, providing high-quality broiler and parent stock to poultry producers worldwide. With a focus on genetic improvement and innovation, Cobb delivers superior performance in growth rate, feed conversion, and overall bird health.',
                'partnership_detail' => 'FlockSense partners with Cobb-Vantress to provide optimized monitoring and management solutions for Cobb breed chickens. Our IoT sensors and analytics are calibrated to track performance metrics specific to Cobb genetics, ensuring farmers can maximize the potential of their flocks.',
                'support_keywords' => ['broiler', 'genetics', 'breeding', 'poultry', 'parent stock', 'feed conversion'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'company_name' => 'Aviagen',
                'url' => 'https://www.aviagen.com',
                'introduction' => 'Aviagen is a world leader in poultry breeding, specializing in the production of broiler, broiler breeder, and turkey breeding stock. Known for brands like Ross and Arbor Acres, Aviagen is committed to sustainable and efficient poultry production through advanced genetics.',
                'partnership_detail' => 'Through our partnership with Aviagen, FlockSense offers breed-specific growth standards and environmental monitoring tailored to Ross, Arbor Acres, and other Aviagen genetics. Our platform helps farmers maintain optimal conditions for these premium breeds, improving overall flock performance and profitability.',
                'support_keywords' => ['Ross', 'Arbor Acres', 'breeding', 'broiler', 'turkey', 'genetics', 'sustainability'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'company_name' => 'Big Dutchman',
                'url' => 'https://www.bigdutchman.com',
                'introduction' => 'Big Dutchman is a global leader in modern poultry and pig farming equipment. With innovative solutions for housing, feeding, climate control, and egg production, Big Dutchman helps farmers improve efficiency, animal welfare, and profitability.',
                'partnership_detail' => 'FlockSense integrates seamlessly with Big Dutchman equipment, enabling real-time monitoring and automated control of feeding systems, climate controllers, and ventilation. Our IoT platform extends the capabilities of Big Dutchman hardware, providing farmers with data-driven insights and remote management features.',
                'support_keywords' => ['equipment', 'feeding systems', 'climate control', 'ventilation', 'automation', 'housing', 'IoT integration'],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'company_name' => 'Vencomatic Group',
                'url' => 'https://www.vencomatic.com',
                'introduction' => 'Vencomatic Group is an innovative Dutch company specializing in poultry equipment and solutions for layer, broiler, and breeder farms. Known for their egg collection systems, housing solutions, and climate control technology, Vencomatic focuses on animal welfare and farm efficiency.',
                'partnership_detail' => 'FlockSense collaborates with Vencomatic to provide comprehensive monitoring for farms using Vencomatic equipment. Our sensors track environmental conditions, egg production, and bird behavior, integrating with Vencomatic systems to deliver actionable insights and optimize farm operations.',
                'support_keywords' => ['layer', 'egg production', 'housing', 'climate control', 'animal welfare', 'equipment', 'sensors'],
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'company_name' => 'Petersime',
                'url' => 'https://www.petersime.com',
                'introduction' => 'Petersime is a world-renowned manufacturer of hatchery equipment and incubation technology. With over 100 years of experience, Petersime provides advanced incubators, hatchers, and automation systems that ensure optimal hatch results and chick quality.',
                'partnership_detail' => 'FlockSense partners with Petersime to extend hatchery monitoring capabilities. Our platform can integrate with Petersime hatchery systems to provide real-time alerts, environmental tracking, and performance analytics, helping hatchery managers maintain ideal conditions for successful hatches.',
                'support_keywords' => ['hatchery', 'incubation', 'hatchers', 'incubators', 'chick quality', 'automation', 'embryo development'],
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($partners as $partnerData) {
            Partner::create($partnerData);
        }

        $this->command->info('✓ Partners seeded successfully: '.count($partners).' partners created.');
    }
}
