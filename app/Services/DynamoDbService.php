<?php

namespace App\Services;

use Aws\DynamoDb\DynamoDbClient;
use Aws\Sdk;
use Illuminate\Support\Facades\Log;

class DynamoDbService
{
    protected $client;
    protected $table = 'sensor-data';

    public function __construct()
    {
        $config = [
            'region'   => config('aws.region'),
            'version'  => 'latest',
            'credentials' => [
                'key'    => config('aws.key'),
                'secret' => config('aws.secret'),
            ],
            'http' => [
                'verify' => false, // Completely disable SSL verification
                'timeout' => 30,
                'connect_timeout' => 10,
            ],
        ];

        $sdk = new Sdk($config);
        $this->client = $sdk->createDynamoDb();
    }

    public function putSensorData(array $data): void
    {
        $item = [
            'device_id'  => ['N' => (string)$data['device_id']],
            'timestamp'  => ['N' => (string)$data['timestamp']],
        ];

        // Dynamically encode all additional sensor fields
        foreach ($data as $key => $value) {
            if (!in_array($key, ['device_id', 'timestamp']) && $value !== null) {
                $item[$key] = ['N' => (string)$value];
            }
        }

        $this->client->putItem([
            'TableName' => $this->table,
            'Item'      => $item,
        ]);
    }

    public function getSensorData(array $deviceIds, ?int $fromTimestamp, bool $latest = false): array
    {
        $results = [];

        foreach ($deviceIds as $deviceId) {
            if ($latest) {
                $query = [
                    'TableName' => $this->table,
                    'KeyConditionExpression' => 'device_id = :device_id',
                    'ExpressionAttributeValues' => [
                        ':device_id' => ['N' => (string)$deviceId],
                    ],
                    'ScanIndexForward' => false,
                    'Limit' => 1,
                ];
            } else {
                $query = [
                    'TableName' => $this->table,
                    'KeyConditionExpression' => 'device_id = :device_id AND #ts >= :from_ts',
                    'ExpressionAttributeNames' => ['#ts' => 'timestamp'],
                    'ExpressionAttributeValues' => [
                        ':device_id' => ['N' => (string)$deviceId],
                        ':from_ts' => ['N' => (string)$fromTimestamp],
                    ],
                    'ScanIndexForward' => true,
                ];
            }

            $response = $this->client->query($query);

            foreach ($response['Items'] as $item) {
                $record = [];
                foreach ($item as $key => $value) {
                    $record[$key] = isset($value['N']) ? (float)$value['N'] : (string)array_values($value)[0];
                }
                $results[] = $record;
            }
        }

        return $results;
    }
}
