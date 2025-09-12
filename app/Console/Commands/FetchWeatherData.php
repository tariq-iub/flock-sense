<?php

namespace App\Console\Commands;

use App\Services\WeatherDataService;
use Illuminate\Console\Command;

class FetchWeatherData extends Command
{
    protected $signature = 'weather:fetch';
    protected $description = 'Fetch weather data for all farms and store in DynamoDB';

    public function handle(WeatherDataService $weatherDataService)
    {
        $this->info('Fetching weather data...');
        $weatherDataService->fetchAndStoreWeather();
        $this->info('Weather data saved to DynamoDB.');
    }
}
