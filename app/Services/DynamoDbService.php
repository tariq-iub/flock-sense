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
        return $this->queryByDeviceIds($this->sensorTable, $deviceIds, $fromTimestamp, $toTimestamp, $latest, $ascOrder);
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

        foreach ($deviceIds as $deviceId) {
            try {
                // Build base query params
                $exprAttrNames = ['#ts' => 'timestamp'];
                $exprAttrValues = [
                    ':device_id' => $this->marshaler->marshalValue((int)$deviceId),
                ];

                // Key condition
                if ($latest) {
                    $keyCondition = 'device_id = :device_id';
                } elseif ($toTimestamp !== null) {
                    $keyCondition = 'device_id = :device_id AND #ts BETWEEN :from_ts AND :to_ts';
                    $exprAttrValues[':from_ts'] = $this->marshaler->marshalValue((int)$fromTimestamp);
                    $exprAttrValues[':to_ts'] = $this->marshaler->marshalValue((int)$toTimestamp);
                } else {
                    $keyCondition = 'device_id = :device_id AND #ts >= :from_ts';
                    $exprAttrValues[':from_ts'] = $this->marshaler->marshalValue((int)$fromTimestamp);
                }

                // Optional filter expression for attribute (e.g., appliance_key)
                $filterExpression = null;
                if (!empty($filterAttributeName) && $filterValue !== null) {
                    // use ExpressionAttributeNames / Values
                    $exprAttrNames['#filter'] = $filterAttributeName;
                    $exprAttrValues[':filter_val'] = $this->marshaler->marshalValue($filterValue);
                    $filterExpression = '#filter = :filter_val';
                }

                // Query loop for pagination (avoid loading huge pages at once)
                $lastEvaluatedKey = null;
                do {
                    $query = [
                        'TableName' => $table,
                        'KeyConditionExpression' => $keyCondition,
                        'ExpressionAttributeNames' => $exprAttrNames,
                        'ExpressionAttributeValues' => $exprAttrValues,
                        'ScanIndexForward' => $ascOrder,
                    ];

                    if ($latest) {
                        $query['Limit'] = 1;
                        $query['ScanIndexForward'] = false; // get newest first
                    }

                    if ($filterExpression) {
                        $query['FilterExpression'] = $filterExpression;
                    }

                    if ($lastEvaluatedKey) {
                        $query['ExclusiveStartKey'] = $lastEvaluatedKey;
                    }

                    $response = $this->client->query($query);

                    if (!empty($response['Items'])) {
                        foreach ($response['Items'] as $item) {
                            // Unmarshal to PHP array
                            $record = $this->marshaler->unmarshalItem($item);

                            // Optionally normalize numeric strings to floats/ints
                            array_walk_recursive($record, function (&$v) {
                                if (is_string($v) && is_numeric($v)) {
                                    // preserve integers if no decimal point
                                    $v = (strpos($v, '.') === false) ? (int)$v : (float)$v;
                                }
                            });

                            $results[] = $record;
                        }
                    }

                    $lastEvaluatedKey = $response['LastEvaluatedKey'] ?? null;

                    // For latest we only want the most recent item => break after first page
                    if ($latest) {
                        break;
                    }
                } while ($lastEvaluatedKey);

            } catch (Exception $e) {
                Log::error("[DynamoDbService] Failed to fetch data for device_id {$deviceId} from table {$table}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $results;
    }
}
