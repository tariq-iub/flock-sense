<?php

return [
    'region' => env('AWS_DEFAULT_REGION', 'ap-south-1'),

    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),

    'http' => [
        'verify' => env('AWS_SSL_VERIFY', false),
        'timeout' => 30,
    ],

    'dynamo' => [
        'sensor_table' => 'sensor-data',
        'appliance_table' => 'device-appliance-status',
    ],
];
