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
        $sdk = new Sdk([
            'region'   => config('aws.region'),
            'version'  => 'latest',
            'credentials' => [
                'key'    => config('aws.key'),
                'secret' => config('aws.secret'),
            ],
        ]);

        $this->client = $sdk->createDynamoDb();
    }

    public function putSensorData(array $data): void
    {
        $item = [
            'device_id'  => ['N' => (string)$data['device_id']],
            'timestamp'  => ['N' => (string)$data['timestamp']],
        ];

        foreach (['temperature', 'humidity', 'co2', 'nh3', 'electricity'] as $field) {
            if (isset($data[$field])) {
                $item[$field] = ['N' => (string)$data[$field]];
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
                    'ScanIndexForward' => false, // newest first
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
                $results[] = [
                    'device_id' => (int)$item['device_id']['N'],
                    'timestamp' => (int)$item['timestamp']['N'],
                    'temperature' => isset($item['temperature']) ? (float)$item['temperature']['N'] : null,
                    'humidity' => isset($item['humidity']) ? (float)$item['humidity']['N'] : null,
                    'co2' => isset($item['co2']) ? (float)$item['co2']['N'] : null,
                    'nh3' => isset($item['nh3']) ? (float)$item['nh3']['N'] : null,
                    'electricity' => isset($item['electricity']) ? (float)$item['electricity']['N'] : null,
                ];
            }
        }

        return $results;
    }
}
