<?php
//
//namespace App\Services;
//
//use Aws\DynamoDb\DynamoDbClient;
//use Aws\Sdk;
//use Illuminate\Support\Facades\Log;
//
//class DynamoDbService
//{
//    protected $client;
//    protected $table = 'sensor-data';
//
//    public function __construct()
//    {
//        $config = [
//            'region'   => config('aws.region'),
//            'version'  => 'latest',
//            'credentials' => [
//                'key'    => config('aws.key'),
//                'secret' => config('aws.secret'),
//            ],
//            'http' => [
//                'verify' => false, // Completely disable SSL verification
//                'timeout' => 30,
//                'connect_timeout' => 10,
//            ],
//        ];
//
//        $sdk = new Sdk($config);
//        $this->client = $sdk->createDynamoDb();
//    }
//
//    public function putSensorData(array $data): void
//    {
//        $item = [
//            'device_id'  => ['N' => (string)$data['device_id']],
//            'timestamp'  => ['N' => (string)$data['timestamp']],
//        ];
//
//        // Dynamically encode all additional sensor fields
//        foreach ($data as $key => $value) {
//            if (!in_array($key, ['device_id', 'timestamp']) && $value !== null) {
//                $item[$key] = ['N' => (string)$value];
//            }
//        }
//
//        $this->client->putItem([
//            'TableName' => $this->table,
//            'Item'      => $item,
//        ]);
//    }
//
//    public function getSensorData(
//        array $deviceIds,
//        ?int $fromTimestamp,
//        ?int $toTimestamp = null,
//        bool $latest = false,
//        bool $ascOrder = true
//    ): array
//    {
//        $results = [];
//
//        if(empty($deviceIds) || $fromTimestamp == null)
//        {
//            return $results;
//        }
//
//        foreach ($deviceIds as $deviceId) {
//            if ($latest) {
//                $query = [
//                    'TableName' => $this->table,
//                    'KeyConditionExpression' => 'device_id = :device_id',
//                    'ExpressionAttributeValues' => [
//                        ':device_id' => ['N' => $deviceId],
//                    ],
//                    'ScanIndexForward' => false,
//                    'Limit' => 1,
//                ];
//            }
//            elseif($toTimestamp != null) {
//                $query = [
//                    'TableName' => $this->table,
//                    'KeyConditionExpression' => 'device_id = :device_id AND #ts BETWEEN :from_ts AND :to_ts',
//                    'ExpressionAttributeNames' => ['#ts' => 'timestamp'],
//                    'ExpressionAttributeValues' => [
//                        ':device_id' => ['N' => (string)$deviceId],
//                        ':from_ts' => ['N' => (string)$fromTimestamp],
//                        ':to_ts' => ['N' => (string)$toTimestamp],
//                    ],
//                    'ScanIndexForward' => $ascOrder,
//                ];
//            }
//            else {
//                $query = [
//                    'TableName' => $this->table,
//                    'KeyConditionExpression' => 'device_id = :device_id AND #ts >= :from_ts',
//                    'ExpressionAttributeNames' => ['#ts' => 'timestamp'],
//                    'ExpressionAttributeValues' => [
//                        ':device_id' => ['N' => (string)$deviceId],
//                        ':from_ts' => ['N' => (string)$fromTimestamp],
//                    ],
//                    'ScanIndexForward' => $ascOrder,
//                ];
//            }
//
//            $response = $this->client->query($query);
//
//            \Log::debug('Fetched sensor data from DynamoDB:', $response['Items'] ?? []);
//
//            foreach ($response['Items'] as $item) {
//                $record = [];
//                foreach ($item as $key => $value) {
//                    $record[$key] = isset($value['N']) ? (float)$value['N'] : (string)array_values($value)[0];
//                }
//                $results[] = $record;
//            }
//        }
//
//        return $results;
//    }
//}


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
            'region' => config('aws.region'),
            'version' => 'latest',
            'credentials' => [
                'key' => config('aws.key'),
                'secret' => config('aws.secret'),
            ],
            'http' => [
                'verify' => false,
                'timeout' => 30,
                'connect_timeout' => 10,
            ],
        ];

        Log::debug('[DynamoDbService] Initializing DynamoDB client with config:', $config);

        $sdk = new Sdk($config);
        $this->client = $sdk->createDynamoDb();

        Log::info('[DynamoDbService] DynamoDB client initialized successfully.');
    }

    public function putSensorData(array $data): void
    {
        Log::info('[DynamoDbService] Storing sensor data:', $data);

        $item = [
            'device_id' => ['N' => (string)$data['device_id']],
            'timestamp' => ['N' => (string)$data['timestamp']],
        ];

        foreach ($data as $key => $value) {
            if (!in_array($key, ['device_id', 'timestamp']) && $value !== null) {
                $item[$key] = ['N' => (string)$value];
            }
        }

        try {
            $this->client->putItem([
                'TableName' => $this->table,
                'Item' => $item,
            ]);
            Log::info('[DynamoDbService] Sensor data stored successfully.', ['item' => $item]);
        } catch (\Exception $e) {
            Log::error('[DynamoDbService] Failed to store sensor data.', [
                'error' => $e->getMessage(),
                'item' => $item
            ]);
        }
    }

    public function getSensorData(
        array $deviceIds,
        ?int  $fromTimestamp,
        ?int  $toTimestamp = null,
        bool  $latest = false,
        bool  $ascOrder = true
    ): array
    {
        Log::info('[DynamoDbService] Fetching sensor data with parameters', [
            'deviceIds' => $deviceIds,
            'from' => $fromTimestamp,
            'to' => $toTimestamp,
            'latest' => $latest,
            'asc' => $ascOrder,
        ]);

        $results = [];

        if (empty($deviceIds) || (!$latest && $fromTimestamp === null)) {
            Log::warning('[DynamoDbService] Missing deviceIds or fromTimestamp. Returning empty result.');
            return $results;
        }

        foreach ($deviceIds as $deviceId) {
            try {
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
                } elseif ($toTimestamp !== null) {
                    $query = [
                        'TableName' => $this->table,
                        'KeyConditionExpression' => 'device_id = :device_id AND #ts BETWEEN :from_ts AND :to_ts',
                        'ExpressionAttributeNames' => ['#ts' => 'timestamp'],
                        'ExpressionAttributeValues' => [
                            ':device_id' => ['N' => (string)$deviceId],
                            ':from_ts' => ['N' => (string)$fromTimestamp],
                            ':to_ts' => ['N' => (string)$toTimestamp],
                        ],
                        'ScanIndexForward' => $ascOrder,
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
                        'ScanIndexForward' => $ascOrder,
                    ];
                }

                Log::debug('[DynamoDbService] Executing query for device:', [
                    'device_id' => $deviceId,
                    'query' => $query
                ]);

                $response = $this->client->query($query);

                if (isset($response['Items']) && count($response['Items']) > 0) {
                    Log::info("[DynamoDbService] Retrieved " . count($response['Items']) . " records for device_id {$deviceId}");
                } else {
                    Log::warning("[DynamoDbService] No data found for device_id {$deviceId}");
                }

                foreach ($response['Items'] as $item) {
                    $record = [];
                    foreach ($item as $key => $value) {
                        $record[$key] = isset($value['N']) ? (float)$value['N'] : (string)array_values($value)[0];
                    }
                    $results[] = $record;
                }
            } catch (\Exception $e) {
                Log::error('[DynamoDbService] Failed to fetch data for device_id ' . $deviceId, [
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::debug('[DynamoDbService] Final fetched sensor data:', $results);

        return $results;
    }
}
