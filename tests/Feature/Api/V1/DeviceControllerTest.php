<?php

use App\Models\User;
use App\Models\Device;
use App\Models\Shed;
use App\Models\DeviceAppliance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);
});

describe('Device API Endpoints', function () {
    it('can list devices with query builder features', function () {
        $device1 = Device::factory()->create(['serial_no' => 'DEV001']);
        $device2 = Device::factory()->create(['serial_no' => 'DEV002']);

        $response = $this->getJson('/api/v1/devices');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'serial_no',
                            'firmware_version',
                            'capabilities',
                            'sheds_count',
                            'appliances_count',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]
            ])
            ->assertJsonCount(2, 'data');
    });

    it('can filter devices by serial number', function () {
        Device::factory()->create(['serial_no' => 'DEV001']);
        Device::factory()->create(['serial_no' => 'DEV002']);

        $response = $this->getJson('/api/v1/devices?filter[serial_no]=DEV001');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    });

    it('can filter devices by firmware version', function () {
        Device::factory()->create(['firmware_version' => 'v1.0.0']);
        Device::factory()->create(['firmware_version' => 'v2.0.0']);

        $response = $this->getJson('/api/v1/devices?filter[firmware_version]=v1.0.0');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    });

    it('can sort devices by serial number', function () {
        Device::factory()->create(['serial_no' => 'DEV002']);
        Device::factory()->create(['serial_no' => 'DEV001']);

        $response = $this->getJson('/api/v1/devices?sort=serial_no');

        $response->assertStatus(200);
        $data = $response->json('data');
        expect($data[0]['attributes']['serial_no'])->toBe('DEV001');
    });

    it('can include sheds relationship', function () {
        $device = Device::factory()->create();
        $shed = Shed::factory()->create();
        $device->sheds()->attach($shed->id);

        $response = $this->getJson('/api/v1/devices?include=sheds');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'attributes' => [
                            'sheds' => [
                                '*' => [
                                    'id',
                                    'name',
                                    'capacity',
                                    'type',
                                    'link_date',
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    });

    it('can include appliances relationship', function () {
        $device = Device::factory()->create();
        DeviceAppliance::factory()->count(2)->create(['device_id' => $device->id]);

        $response = $this->getJson('/api/v1/devices?include=appliances');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'attributes' => [
                            'appliances' => [
                                '*' => [
                                    'id',
                                    'type',
                                    'name',
                                    'channel',
                                    'config',
                                    'status',
                                    'metrics',
                                    'status_updated_at',
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    });

    it('can create a new device', function () {
        $deviceData = [
            'serial_no' => 'DEV001',
            'firmware_version' => 'v1.0.0',
            'capabilities' => ['temperature', 'humidity', 'nh3'],
        ];

        $response = $this->postJson('/api/v1/devices', $deviceData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Device created successfully.',
                'device' => [
                    'data' => [
                        'attributes' => [
                            'serial_no' => 'DEV001',
                            'firmware_version' => 'v1.0.0',
                            'capabilities' => ['temperature', 'humidity', 'nh3'],
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('devices', [
            'serial_no' => 'DEV001',
            'firmware_version' => 'v1.0.0',
        ]);
    });

    it('validates required fields when creating device', function () {
        $response = $this->postJson('/api/v1/devices', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['serial_no', 'capabilities']);
    });

    it('validates unique serial number', function () {
        Device::factory()->create(['serial_no' => 'DEV001']);

        $response = $this->postJson('/api/v1/devices', [
            'serial_no' => 'DEV001',
            'capabilities' => ['temperature'],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['serial_no']);
    });

    it('validates capabilities is an array', function () {
        $response = $this->postJson('/api/v1/devices', [
            'serial_no' => 'DEV001',
            'capabilities' => 'not_an_array',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['capabilities']);
    });

    it('can show device with detailed relationships', function () {
        $device = Device::factory()->create();
        $shed = Shed::factory()->create();
        $device->sheds()->attach($shed->id);
        $appliance = DeviceAppliance::factory()->create(['device_id' => $device->id]);

        $response = $this->getJson("/api/v1/devices/{$device->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'serial_no',
                        'firmware_version',
                        'capabilities',
                        'sheds_count',
                        'appliances_count',
                        'created_at',
                        'updated_at',
                        'sheds' => [
                            '*' => [
                                'id',
                                'name',
                                'capacity',
                                'type',
                                'link_date',
                            ]
                        ],
                        'appliances' => [
                            '*' => [
                                'id',
                                'type',
                                'name',
                                'channel',
                                'config',
                                'status',
                                'metrics',
                                'status_updated_at',
                            ]
                        ]
                    ]
                ]
            ]);
    });

    it('can update device', function () {
        $device = Device::factory()->create();

        $updateData = [
            'serial_no' => 'DEV002',
            'firmware_version' => 'v2.0.0',
            'capabilities' => ['temperature', 'humidity', 'co2'],
        ];

        $response = $this->putJson("/api/v1/devices/{$device->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Device updated successfully.',
                'device' => [
                    'data' => [
                        'attributes' => [
                            'serial_no' => 'DEV002',
                            'firmware_version' => 'v2.0.0',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'serial_no' => 'DEV002',
            'firmware_version' => 'v2.0.0',
        ]);
    });

    it('validates unique serial number on update', function () {
        $device1 = Device::factory()->create(['serial_no' => 'DEV001']);
        $device2 = Device::factory()->create(['serial_no' => 'DEV002']);

        $response = $this->putJson("/api/v1/devices/{$device1->id}", [
            'serial_no' => 'DEV002',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['serial_no']);
    });

    it('can delete device', function () {
        $device = Device::factory()->create();

        $response = $this->deleteJson("/api/v1/devices/{$device->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Device deleted successfully.',
            ]);

        $this->assertDatabaseMissing('devices', ['id' => $device->id]);
    });

    it('returns 404 for non-existent device', function () {
        $response = $this->getJson('/api/v1/devices/99999');

        $response->assertStatus(404);
    });

    it('requires authentication for protected endpoints', function () {
        $this->actingAs($this->user)->get('/api/v1/devices'); // Ensure we're authenticated first
        auth()->guard('web')->logout();

        $response = $this->getJson('/api/v1/devices');

        $response->assertStatus(401);
    });
}); 