<?php

namespace App\Services;

use Aws\Sdk;
use Aws\DynamoDb\Marshaler;
use Illuminate\Support\Facades\Log;
use Exception;

class DynamoDbService
{
    protected $client;
    protected $marshaler;

    /**
     * DynamoDB table names (default values can be changed via config)
     * sensorTable: stores sensor datapoints
     * applianceTable: stores appliance status history
     */
    protected string $sensorTable;
    protected string $applianceTable;

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

        $sdk = new Sdk($config);
        $this->client = $sdk->createDynamoDb();

        // Marshaler to convert PHP -> DynamoDB types and back
        $this->marshaler = new Marshaler();

        // Table names (can be overridden via config if you want)
        $this->sensorTable = config('aws.dynamo.sensor_table', 'sensor-data');
        $this->applianceTable = config('aws.dynamo.appliance_table', 'device-appliance-status');

    }

    /**
     * Put sensor data into DynamoDB sensor table.
     * $data should include at minimum: device_id (int) and timestamp (int)
     * other keys (temperature, humidity, etc) can be present as scalars or nested arrays
     */
    public function putSensorData(array $data): void
    {
        if (empty($data['device_id'])) {
            return;
        }

        try {
            $item = $this->marshaler->marshalItem($data);

            $this->client->putItem([
                'TableName' => $this->sensorTable,
                'Item' => $item,
            ]);
        } catch (Exception $e) {
            Log::error('[DynamoDbService] Failed to store sensor data.', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }

    /**
     * Store an appliance status record (history) in DynamoDB appliance table.
     * $data must include: device_id (int), timestamp (int), appliance_key (string), status (bool)
     * optional: metrics (array), source (string), etc.
     */
    public function putApplianceData(array $data): void
    {
        if (empty($data['device_id'])) {
            return;
        }

        try {
            $item = $this->marshaler->marshalItem($data);

            $this->client->putItem([
                'TableName' => $this->applianceTable,
                'Item' => $item,
            ]);
        } catch (Exception $e) {
            Log::error('[DynamoDbService] Failed to store appliance status.', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }

    /**
     * Get sensor data for one or more device_ids.
     *
     * @param array $deviceIds
     * @param int|null $fromTimestamp required unless $latest is true
     * @param int|null $toTimestamp optional
     * @param bool $latest if true returns only latest record per device
     * @param bool $ascOrder
     * @return array Array of unmarshalled items (PHP associative arrays)
     */
    public function getSensorData(array $deviceIds, ?int $fromTimestamp, ?int $toTimestamp = null, bool $latest = false, bool $ascOrder = true): array
    {
        $results = [];

        if (empty($deviceIds)) {
            return $results;
        }

        foreach ($deviceIds as $deviceId) {
            try {
                $query = [
                    'TableName' => 'sensor-data',  // Explicit table name
                    'KeyConditionExpression' => 'device_id = :device_id',
                    'ExpressionAttributeValues' => [
                        ':device_id' => $this->marshaler->marshalValue((int)$deviceId),
                    ],
                    'ScanIndexForward' => $ascOrder,
                ];

                // If latest, get most recent record only
                if ($latest) {
                    $query['Limit'] = 1;
                    $query['ScanIndexForward'] = false;  // Newest first
                } // Add timestamp range if provided
                elseif ($fromTimestamp !== null) {
                    $query['KeyConditionExpression'] .= ' AND #ts >= :from_ts';
                    $query['ExpressionAttributeNames'] = ['#ts' => 'timestamp'];
                    $query['ExpressionAttributeValues'][':from_ts'] =
                        $this->marshaler->marshalValue((int)$fromTimestamp);

                    if ($toTimestamp !== null) {
                        $query['KeyConditionExpression'] = 'device_id = :device_id AND #ts BETWEEN :from_ts AND :to_ts';
                        $query['ExpressionAttributeValues'][':to_ts'] =
                            $this->marshaler->marshalValue((int)$toTimestamp);
                    }
                }

                $response = $this->client->query($query);

                if (!empty($response['Items'])) {
                    $record = $this->marshaler->unmarshalItem($response['Items'][0]);

                    // Normalize numeric strings
                    array_walk_recursive($record, function (&$v) {
                        if (is_string($v) && is_numeric($v)) {
                            $v = (strpos($v, '.') === false) ? (int)$v : (float)$v;
                        }
                    });

                    $results[$deviceId] = $record;  // ✅ Key by device_id
                } else {
                    $results[$deviceId] = null;
                }

            } catch (Exception $e) {
                Log::error("[DynamoDbService] Failed to fetch sensor data for device_id {$deviceId}", [
                    'error' => $e->getMessage(),
                ]);
                $results[$deviceId] = null;
            }
        }

        return $results;  // Returns [deviceId => record] format
    }

    /**
     * Get appliance history for device(s).
     *
     * @param array $deviceIds
     * @param int|null $fromTimestamp
     * @param int|null $toTimestamp
     * @param bool $latest
     * @param string|null $applianceKey optional: filter by appliance_key (will be FilterExpression)
     * @param bool $ascOrder
     * @return array
     */
    public function getApplianceHistory(array $deviceIds, ?int $fromTimestamp, ?int $toTimestamp = null, bool $latest = false, ?string $applianceKey = null, bool $ascOrder = true): array
    {
        return $this->queryByDeviceIds($this->applianceTable, $deviceIds, $fromTimestamp, $toTimestamp, $latest, $ascOrder, $applianceKey, 'appliance_key');
    }

    /**
     * Fetch the latest sensor data record for one or more devices.
     */
    public function getLatestSensorData(array $deviceIds): array
    {
        $results = [];

        if (empty($deviceIds)) {
            return $results;
        }

        foreach ($deviceIds as $deviceId) {
            try {
                $query = [
                    'TableName' => 'sensor-data', // ✅ use correct name here
                    'KeyConditionExpression' => 'device_id = :device_id',
                    'ExpressionAttributeValues' => [
                        ':device_id' => $this->marshaler->marshalValue((int)$deviceId),
                    ],
                    'ScanIndexForward' => false, // newest first
                    'Limit' => 1,
                ];

                $response = $this->client->query($query);

                if (!empty($response['Items']) && isset($response['Items'][0])) {
                    $item = $response['Items'][0];
                    $record = $this->marshaler->unmarshalItem($item);

                    array_walk_recursive($record, function (&$v) {
                        if (is_string($v) && is_numeric($v)) {
                            $v = (strpos($v, '.') === false) ? (int)$v : (float)$v;
                        }
                    });

                    $results[$deviceId] = $record;
                } else {
                    $results[$deviceId] = null;
                }

            } catch (Exception $e) {
                Log::error("[DynamoDbService] Failed to fetch latest sensor data for device_id {$deviceId}", [
                    'error' => $e->getMessage(),
                ]);
                $results[$deviceId] = null;
            }
        }

        return $results;
    }

    /**
     * Convenience: get latest appliance status for a single device + optional key
     */
    public function getLatestApplianceStatus(int $deviceId, ?string $applianceKey = null)
    {
        $items = $this->getApplianceHistory([$deviceId], null, null, true, $applianceKey, false);
        return $items[0] ?? null;
    }

    /**
     * Internal helper to query a table by device_id and timestamp ranges.
     * Supports optional FilterExpression on a given attribute name.
     */
    protected function queryByDeviceIds(
        string  $table,
        array   $deviceIds,
        ?int    $fromTimestamp,
        ?int    $toTimestamp = null,
        bool    $latest = false,
        bool    $ascOrder = true,
        ?string $filterValue = null,
        ?string $filterAttributeName = null
    ): array
    {
        $results = [];

        if (empty($deviceIds) || (!$latest && $fromTimestamp === null)) {
            return $results;
        }

        // Optional: allow override via config if you have a different pk or index
        $partitionKeyName = config('aws.dynamo.partition_key', 'device_id');
        $indexName = config('aws.dynamo.index_name', null); // optional GSI
        $consistentRead = config('aws.dynamo.consistent_read', false);

        foreach ($deviceIds as $deviceId) {
            try {
                // Expression attribute names (include pk as placeholder too to be safe)
                $exprAttrNames = [
                    '#pk' => $partitionKeyName,
                    '#ts' => 'timestamp',
                ];

                // Build key condition template
                if ($latest) {
                    $keyCondition = '#pk = :device_id';
                } elseif ($toTimestamp !== null) {
                    $keyCondition = '#pk = :device_id AND #ts BETWEEN :from_ts AND :to_ts';
                } else {
                    $keyCondition = '#pk = :device_id AND #ts >= :from_ts';
                }

                // Prepare range values if needed (don't cast to int blindly — we'll marshal safely)
                $rangeValues = [];
                if (!$latest) {
                    $rangeValues[':from_ts'] = $this->marshaler->marshalValue((int)$fromTimestamp);
                    if ($toTimestamp !== null) {
                        $rangeValues[':to_ts'] = $this->marshaler->marshalValue((int)$toTimestamp);
                    }
                }

                // Filter expression (optional)
                $filterExpression = null;
                $filterValueMarshalled = null;
                if (!empty($filterAttributeName) && $filterValue !== null) {
                    $exprAttrNames['#filter'] = $filterAttributeName;
                    $filterExpression = '#filter = :filter_val';
                    $filterValueMarshalled = $this->marshaler->marshalValue($filterValue);
                }

                // We'll try at most two attempts for partition key types:
                //  - numeric (N)
                //  - string (S)
                $attempts = [
                    $this->marshaler->marshalValue(is_int($deviceId) ? $deviceId : (int)$deviceId),
                    $this->marshaler->marshalValue((string)$deviceId),
                ];

                $foundItemsForDevice = [];

                foreach ($attempts as $attemptIndex => $pkValue) {
                    $lastEvaluatedKey = null;

                    do {
                        // Build ExpressionAttributeValues for this page/attempt
                        $exprAttrValues = $rangeValues + [':device_id' => $pkValue];
                        if ($filterExpression) {
                            $exprAttrValues[':filter_val'] = $filterValueMarshalled;
                        }

                        $query = [
                            'TableName' => $table,
                            'KeyConditionExpression' => $keyCondition,
                            'ExpressionAttributeNames' => $exprAttrNames,
                            'ExpressionAttributeValues' => $exprAttrValues,
                            'ScanIndexForward' => $ascOrder,
                            'ConsistentRead' => $consistentRead,
                        ];

                        if ($indexName) {
                            $query['IndexName'] = $indexName;
                        }

                        if ($latest) {
                            // We want the latest; get newest first. If filter exists we may need to page further.
                            $query['Limit'] = 1;
                            $query['ScanIndexForward'] = false;
                        }

                        if ($filterExpression) {
                            $query['FilterExpression'] = $filterExpression;
                        }

                        if ($lastEvaluatedKey) {
                            $query['ExclusiveStartKey'] = $lastEvaluatedKey;
                        }

                        // Helpful debug log to inspect what we sent (remove in prod if noisy)
                        \Log::debug('[DynamoDbService] Querying DynamoDB', [
                            'table' => $table,
                            'device' => $deviceId,
                            'attempt' => $attemptIndex === 0 ? 'numeric' : 'string',
                            'query' => $query,
                        ]);

                        $response = $this->client->query($query);

                        if (!empty($response['Items'])) {
                            foreach ($response['Items'] as $item) {
                                $record = $this->marshaler->unmarshalItem($item);
                                // Optional: normalize numeric strings
                                array_walk_recursive($record, function (&$v) {
                                    if (is_string($v) && is_numeric($v)) {
                                        $v = (strpos($v, '.') === false) ? (int)$v : (float)$v;
                                    }
                                });
                                $foundItemsForDevice[] = $record;
                            }
                        }

                        $lastEvaluatedKey = $response['LastEvaluatedKey'] ?? null;

                        // If latest requested:
                        // - If no filterExpression: we only needed the first page -> break to next device.
                        // - If filterExpression: continue paginating until we find a matching item or pages exhausted.
                        if ($latest) {
                            if (!$filterExpression) {
                                // we wanted the single newest item only
                                break; // break out of attempts+device loops and use found items
                            } else {
                                // if we found at least one item matching filter in this attempt, we're done for this device
                                if (!empty($foundItemsForDevice)) {
                                    break;
                                }
                                // else continue paging if AWS says there are more pages
                            }
                        }
                    } while ($lastEvaluatedKey);
                    // if items found in this attempt and latest=false, we still append all pages; continue attempts no longer necessary
                    if (!empty($foundItemsForDevice) && !$latest) {
                        break; // found data with current attempt, no need to try alternate key type
                    }
                } // end attempts

                // append device's found items to overall results
                if ($latest) {
                    $results[$deviceId] = $foundItemsForDevice[0] ?? null;
                } else {
                    $results[$deviceId] = $foundItemsForDevice;
                }
            } catch (\Exception $e) {
                \Log::error("[DynamoDbService] Failed to fetch data for device_id {$deviceId} from table {$table}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        } // end device loop

        return $results;
    }
}
