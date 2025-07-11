<?php

use App\Models\User;
use App\Models\Device;
use App\Models\Farm;
use App\Models\Shed;
use App\Models\ShedDevice;
use App\Services\DynamoDbService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Mockery;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->farm = Farm::factory()->create(['owner_id' => $this->user->id]);
    $this->shed = Shed::factory()->create(['farm_id' => $this->farm->id]);
    $this->device = Device::factory()->create(['serial_no' => 'DEV001']);
    ShedDevice::factory()->create([
        'shed_id' => $this->shed->id,
        'device_id' => $this->device->id,
    ]);
    Sanctum::actingAs($this->user);
});

describe('SensorData API Endpoints', function () {
    it('can store sensor data', function () {
        $sensorData = [
            'serial_no' => $this->device->serial_no,
            'timestamp' => time(),
            'temperature' => 25.5,
            'humidity' => 60.0,
            'nh3' => 10.2,
            'co2' => 800.0,
            'electricity' => 220.0,
        ];

        $response = $this->postJson('/api/v1/sensor-data', $sensorData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Sensor data stored successfully.',
            ]);
    });

    it('validates required fields when storing sensor data', function () {
        $response = $this->postJson('/api/v1/sensor-data', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['serial_no', 'timestamp']);
    });

    it('validates device exists when storing sensor data', function () {
        $response = $this->postJson('/api/v1/sensor-data', [
            'serial_no' => 'NONEXISTENT',
            'timestamp' => time(),
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Device not found.',
            ]);
    });

    it('validates timestamp is integer when storing sensor data', function () {
        $response = $this->postJson('/api/v1/sensor-data', [
            'serial_no' => $this->device->serial_no,
            'timestamp' => 'not_an_integer',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['timestamp']);
    });

    it('can fetch sensor data by shed with latest range', function () {
        $this->mockDynamoDbService();

        $response = $this->getJson("/api/v1/sensor-data/shed/{$this->shed->id}?range=latest");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'device_id',
                            'timestamp',
                            'temperature',
                            'humidity',
                            'nh3',
                            'co2',
                            'electricity',
                            'created_at',
                        ]
                    ]
                ]
            ]);
    });

    it('can fetch sensor data by shed with time range', function () {
        $this->mockDynamoDbService();

        $response = $this->getJson("/api/v1/sensor-data/shed/{$this->shed->id}?range=last_hour");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'device_id',
                            'timestamp',
                            'temperature',
                            'humidity',
                            'nh3',
                            'co2',
                            'electricity',
                            'created_at',
                        ]
                    ]
                ]
            ]);
    });

    it('validates range parameter when fetching by shed', function () {
        $response = $this->getJson("/api/v1/sensor-data/shed/{$this->shed->id}?range=invalid_range");

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['range']);
    });

    it('returns empty data when shed has no devices', function () {
        $emptyShed = Shed::factory()->create(['farm_id' => $this->farm->id]);

        $response = $this->getJson("/api/v1/sensor-data/shed/{$emptyShed->id}?range=latest");

        $response->assertStatus(200)
            ->assertJson([
                'data' => []
            ]);
    });

    it('can fetch sensor data by farm with latest range', function () {
        $this->mockDynamoDbService();

        $response = $this->getJson("/api/v1/sensor-data/farm/{$this->farm->id}?range=latest");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'device_id',
                            'timestamp',
                            'temperature',
                            'humidity',
                            'nh3',
                            'co2',
                            'electricity',
                            'created_at',
                        ]
                    ]
                ]
            ]);
    });

    it('can fetch sensor data by farm with time range', function () {
        $this->mockDynamoDbService();

        $response = $this->getJson("/api/v1/sensor-data/farm/{$this->farm->id}?range=last_12_hours");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'device_id',
                            'timestamp',
                            'temperature',
                            'humidity',
                            'nh3',
                            'co2',
                            'electricity',
                            'created_at',
                        ]
                    ]
                ]
            ]);
    });

    it('validates range parameter when fetching by farm', function () {
        $response = $this->getJson("/api/v1/sensor-data/farm/{$this->farm->id}?range=invalid_range");

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['range']);
    });

    it('returns empty data when farm has no sheds', function () {
        $emptyFarm = Farm::factory()->create(['owner_id' => $this->user->id]);

        $response = $this->getJson("/api/v1/sensor-data/farm/{$emptyFarm->id}?range=latest");

        $response->assertStatus(200)
            ->assertJson([
                'data' => []
            ]);
    });

    it('returns empty data when farm sheds have no devices', function () {
        $emptyShed = Shed::factory()->create(['farm_id' => $this->farm->id]);

        $response = $this->getJson("/api/v1/sensor-data/farm/{$this->farm->id}?range=latest");

        $response->assertStatus(200)
            ->assertJson([
                'data' => []
            ]);
    });

    it('handles DynamoDB service errors gracefully', function () {
        $this->mockDynamoDbServiceWithError();

        $response = $this->getJson("/api/v1/sensor-data/farm/{$this->farm->id}?range=latest");

        $response->assertStatus(500)
            ->assertJson([
                'message' => 'Error fetching farm sensor data',
            ]);
    });

    it('only allows access to farms owned by authenticated user', function () {
        $otherUser = User::factory()->create();
        $otherFarm = Farm::factory()->create(['owner_id' => $otherUser->id]);

        $response = $this->getJson("/api/v1/sensor-data/farm/{$otherFarm->id}?range=latest");

        $response->assertStatus(404);
    });

    it('requires authentication for protected endpoints', function () {
        $this->actingAs($this->user)->get('/api/v1/sensor-data'); // Ensure we're authenticated first
        auth()->guard('web')->logout();

        $response = $this->postJson('/api/v1/sensor-data', [
            'serial_no' => $this->device->serial_no,
            'timestamp' => time(),
        ]);

        $response->assertStatus(401);
    });

    it('supports all valid time ranges', function () {
        $this->mockDynamoDbService();

        $validRanges = ['latest', 'last_hour', 'last_12_hours', 'day', 'week', 'month'];

        foreach ($validRanges as $range) {
            $response = $this->getJson("/api/v1/sensor-data/shed/{$this->shed->id}?range={$range}");
            $response->assertStatus(200);
        }
    });

    it('handles multiple devices in a shed', function () {
        $device2 = Device::factory()->create(['serial_no' => 'DEV002']);
        ShedDevice::factory()->create([
            'shed_id' => $this->shed->id,
            'device_id' => $device2->id,
        ]);

        $this->mockDynamoDbService();

        $response = $this->getJson("/api/v1/sensor-data/shed/{$this->shed->id}?range=latest");

        $response->assertStatus(200);
        // Should return data from both devices
    });

    it('handles multiple sheds in a farm', function () {
        $shed2 = Shed::factory()->create(['farm_id' => $this->farm->id]);
        $device2 = Device::factory()->create(['serial_no' => 'DEV002']);
        ShedDevice::factory()->create([
            'shed_id' => $shed2->id,
            'device_id' => $device2->id,
        ]);

        $this->mockDynamoDbService();

        $response = $this->getJson("/api/v1/sensor-data/farm/{$this->farm->id}?range=latest");

        $response->assertStatus(200);
        // Should return data from devices in both sheds
    });
});

// Helper methods for mocking DynamoDB service
function mockDynamoDbService() {
    $mockData = [
        [
            'device_id' => 1,
            'timestamp' => time(),
            'temperature' => 25.5,
            'humidity' => 60.0,
            'nh3' => 10.2,
            'co2' => 800.0,
            'electricity' => 220.0,
        ]
    ];

    $mock = Mockery::mock(DynamoDbService::class);
    $mock->shouldReceive('getSensorData')->andReturn($mockData);
    
    app()->instance(DynamoDbService::class, $mock);
}

function mockDynamoDbServiceWithError() {
    $mock = Mockery::mock(DynamoDbService::class);
    $mock->shouldReceive('getSensorData')->andThrow(new \Exception('DynamoDB error'));
    
    app()->instance(DynamoDbService::class, $mock);
} 