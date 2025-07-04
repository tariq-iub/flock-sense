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
    $this->device = Device::factory()->create(['serial_no' => 'DEV001']);
    Sanctum::actingAs($this->user);
});

describe('DeviceAppliance API Endpoints', function () {
    it('can list device appliances', function () {
        DeviceAppliance::factory()->count(3)->create(['device_id' => $this->device->id]);

        $response = $this->getJson('/api/v1/device-appliances');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'type',
                            'name',
                            'channel',
                            'config',
                            'status',
                            'metrics',
                            'status_updated_at',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]
            ])
            ->assertJsonCount(3, 'data');
    });

    it('can create a new device appliance', function () {
        $applianceData = [
            'device_id' => $this->device->id,
            'type' => 'fan',
            'name' => 'Main Fan',
            'channel' => 1,
            'config' => ['speed' => 'variable'],
            'status' => true,
            'metrics' => ['speed' => 3, 'rpm' => 1200],
        ];

        $response = $this->postJson('/api/v1/device-appliances', $applianceData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'type',
                        'name',
                        'channel',
                        'config',
                        'status',
                        'metrics',
                        'status_updated_at',
                    ]
                ]
            ]);

        $this->assertDatabaseHas('device_appliances', [
            'device_id' => $this->device->id,
            'type' => 'fan',
            'name' => 'Main Fan',
            'status' => true,
        ]);
    });

    it('validates required fields when creating appliance', function () {
        $response = $this->postJson('/api/v1/device-appliances', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['device_id', 'type']);
    });

    it('validates device exists when creating appliance', function () {
        $response = $this->postJson('/api/v1/device-appliances', [
            'device_id' => 99999,
            'type' => 'fan',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['device_id']);
    });

    it('can show device appliance', function () {
        $appliance = DeviceAppliance::factory()->create(['device_id' => $this->device->id]);

        $response = $this->getJson("/api/v1/device-appliances/{$appliance->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'type',
                        'name',
                        'channel',
                        'config',
                        'status',
                        'metrics',
                        'status_updated_at',
                        'created_at',
                        'updated_at',
                        'device' => [
                            'id',
                            'serial_no',
                            'firmware_version',
                            'capabilities',
                        ]
                    ]
                ]
            ]);
    });

    it('can update device appliance', function () {
        $appliance = DeviceAppliance::factory()->create(['device_id' => $this->device->id]);

        $updateData = [
            'name' => 'Updated Fan',
            'config' => ['speed' => 'high'],
            'status' => false,
            'metrics' => ['speed' => 0],
        ];

        $response = $this->putJson("/api/v1/device-appliances/{$appliance->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'attributes' => [
                        'name' => 'Updated Fan',
                        'status' => false,
                    ]
                ]
            ]);

        $this->assertDatabaseHas('device_appliances', [
            'id' => $appliance->id,
            'name' => 'Updated Fan',
            'status' => false,
        ]);
    });

    it('can delete device appliance', function () {
        $appliance = DeviceAppliance::factory()->create(['device_id' => $this->device->id]);

        $response = $this->deleteJson("/api/v1/device-appliances/{$appliance->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Appliance deleted successfully',
            ]);

        $this->assertDatabaseMissing('device_appliances', ['id' => $appliance->id]);
    });

    it('can fetch appliances by shed ID', function () {
        $shed = Shed::factory()->create();
        $device2 = Device::factory()->create();
        $shed->devices()->attach([$this->device->id, $device2->id]);
        
        DeviceAppliance::factory()->create(['device_id' => $this->device->id]);
        DeviceAppliance::factory()->create(['device_id' => $device2->id]);

        $response = $this->getJson("/api/v1/shed/{$shed->id}/appliances");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    });

    it('can fetch appliances by device serial', function () {
        DeviceAppliance::factory()->count(2)->create(['device_id' => $this->device->id]);

        $response = $this->getJson("/api/v1/device/{$this->device->serial_no}/appliances");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    });

    it('returns 404 for non-existent device serial', function () {
        $response = $this->getJson('/api/v1/device/NONEXISTENT/appliances');

        $response->assertStatus(404);
    });

    it('can fetch device appliance IDs by device serial', function () {
        $appliance1 = DeviceAppliance::factory()->create(['device_id' => $this->device->id]);
        $appliance2 = DeviceAppliance::factory()->create(['device_id' => $this->device->id]);

        $response = $this->getJson("/api/v1/device/{$this->device->serial_no}/appliance-ids");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [$appliance1->id, $appliance2->id]
            ]);
    });

    it('can update appliance status (IoT endpoint - unauthenticated)', function () {
        $appliance = DeviceAppliance::factory()->create([
            'device_id' => $this->device->id,
            'status' => false
        ]);

        $statusData = [
            'status' => true,
            'metrics' => ['speed' => 3, 'rpm' => 1200],
        ];

        $response = $this->putJson("/api/v1/device-appliances/{$appliance->id}/status", $statusData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'attributes' => [
                        'status' => true,
                        'metrics' => ['speed' => 3, 'rpm' => 1200],
                    ]
                ]
            ]);

        $this->assertDatabaseHas('device_appliances', [
            'id' => $appliance->id,
            'status' => true,
        ]);
    });

    it('validates status field when updating appliance status', function () {
        $appliance = DeviceAppliance::factory()->create(['device_id' => $this->device->id]);

        $response = $this->putJson("/api/v1/device-appliances/{$appliance->id}/status", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    });

    it('can update multiple appliance statuses (IoT endpoint - unauthenticated)', function () {
        $appliance1 = DeviceAppliance::factory()->create(['device_id' => $this->device->id, 'status' => false]);
        $appliance2 = DeviceAppliance::factory()->create(['device_id' => $this->device->id, 'status' => false]);

        $statusesData = [
            'statuses' => [
                [
                    'appliance_id' => $appliance1->id,
                    'status' => true,
                    'metrics' => ['speed' => 3],
                ],
                [
                    'appliance_id' => $appliance2->id,
                    'status' => true,
                    'metrics' => ['speed' => 2],
                ],
            ]
        ];

        $response = $this->putJson('/api/v1/device-appliances/statuses/update', $statusesData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Statuses updated successfully',
            ]);

        $this->assertDatabaseHas('device_appliances', [
            'id' => $appliance1->id,
            'status' => true,
        ]);
        $this->assertDatabaseHas('device_appliances', [
            'id' => $appliance2->id,
            'status' => true,
        ]);
    });

    it('validates statuses array when updating multiple statuses', function () {
        $response = $this->putJson('/api/v1/device-appliances/statuses/update', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['statuses']);
    });

    it('validates appliance exists when updating multiple statuses', function () {
        $response = $this->putJson('/api/v1/device-appliances/statuses/update', [
            'statuses' => [
                [
                    'appliance_id' => 99999,
                    'status' => true,
                ]
            ]
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['statuses.0.appliance_id']);
    });

    it('can get appliance status (IoT endpoint - unauthenticated)', function () {
        $appliance = DeviceAppliance::factory()->create([
            'device_id' => $this->device->id,
            'status' => true,
            'metrics' => ['speed' => 3],
        ]);

        $response = $this->getJson("/api/v1/device-appliances/{$appliance->id}/status");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $appliance->id,
                    'device_appliance_id' => $appliance->id,
                    'status' => true,
                    'metrics' => ['speed' => 3],
                ]
            ]);
    });

    it('can get all appliance statuses (IoT endpoint - unauthenticated)', function () {
        $appliance1 = DeviceAppliance::factory()->create(['device_id' => $this->device->id, 'status' => true]);
        $appliance2 = DeviceAppliance::factory()->create(['device_id' => $this->device->id, 'status' => false]);

        $response = $this->getJson('/api/v1/device-appliances/statuses');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    });

    it('returns 404 for non-existent appliance', function () {
        $response = $this->getJson('/api/v1/device-appliances/99999');

        $response->assertStatus(404);
    });

    it('allows unauthenticated access to IoT endpoints', function () {
        $appliance = DeviceAppliance::factory()->create(['device_id' => $this->device->id]);
        
        // Logout to test unauthenticated access
        auth()->guard('web')->logout();

        $response = $this->putJson("/api/v1/device-appliances/{$appliance->id}/status", [
            'status' => true
        ]);

        $response->assertStatus(200);
    });

    it('updates status_updated_at when status is changed', function () {
        $appliance = DeviceAppliance::factory()->create([
            'device_id' => $this->device->id,
            'status' => false,
            'status_updated_at' => now()->subHour(),
        ]);

        $oldUpdatedAt = $appliance->status_updated_at;

        $response = $this->putJson("/api/v1/device-appliances/{$appliance->id}/status", [
            'status' => true
        ]);

        $response->assertStatus(200);

        $appliance->refresh();
        expect($appliance->status_updated_at->timestamp)->toBeGreaterThan($oldUpdatedAt->timestamp);
    });
}); 