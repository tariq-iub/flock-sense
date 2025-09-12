<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IotDataAggregatorService;

class AggregateIotData extends Command
{
    protected $signature = 'iot:aggregate-data';
    protected $description = 'Aggregate IoT sensor data from DynamoDB into iot_data_logs table';

    public function __construct(protected IotDataAggregatorService $aggregatorService)
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting IoT data aggregation...');
        $this->aggregatorService->aggregate();
        $this->info('IoT data aggregation completed.');
    }
}
