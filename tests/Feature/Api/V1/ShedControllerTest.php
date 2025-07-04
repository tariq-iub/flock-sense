<?php

use App\Models\User;
use App\Models\Farm;
use App\Models\Shed;
use App\Models\Device;
use App\Models\Flock;
use App\Models\Breed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->farm = Farm::factory()->create(['owner_id' => $this->user->id]);
    Sanctum::actingAs($this->user);
});

describe('Shed API Endpoints', function () {
    it('can list sheds with query builder features', function () {
        $shed1 = Shed::factory()->create(['farm_id' => $this->farm->id, 'name' => 'Shed Alpha']);
        $shed2 = Shed::factory()->create(['farm_id' => $this->farm->id, 'name' => 'Shed Beta']);
        
        // Create shed for different farm (should not appear in results)
        $otherFarm = Farm::factory()->create(['owner_id' => User::factory()->create()->id]);
        Shed::factory()->create(['farm_id' => $otherFarm->id]);

        $response = $this->getJson('/api/v1/sheds');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'capacity',
                            'type',
                            'description',
                            'flocks_count',
                            'devices_count',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]
            ])
            ->assertJsonCount(2, 'data');
    });

    it('can filter sheds by name', function () {
        Shed::factory()->create(['farm_id' => $this->farm->id, 'name' => 'Alpha Shed']);
        Shed::factory()->create(['farm_id' => $this->farm->id, 'name' => 'Beta Shed']);

        $response = $this->getJson('/api/v1/sheds?filter[name]=Alpha');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    });

    it('can filter sheds by type', function () {
        Shed::factory()->create(['farm_id' => $this->farm->id, 'type' => 'broiler']);
        Shed::factory()->create(['farm_id' => $this->farm->id, 'type' => 'layer']);

        $response = $this->getJson('/api/v1/sheds?filter[type]=broiler');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    });

    it('can sort sheds by capacity', function () {
        Shed::factory()->create(['farm_id' => $this->farm->id, 'capacity' => 1000]);
        Shed::factory()->create(['farm_id' => $this->farm->id, 'capacity' => 500]);

        $response = $this->getJson('/api/v1/sheds?sort=capacity');

        $response->assertStatus(200);
        $data = $response->json('data');
        expect($data[0]['attributes']['capacity'])->toBe(500);
    });

    it('can include farm relationship', function () {
        $shed = Shed::factory()->create(['farm_id' => $this->farm->id]);

        $response = $this->getJson('/api/v1/sheds?include=farm');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'attributes' => [
                            'farm' => [
                                'id',
                                'name',
                                'address',
                            ]
                        ]
                    ]
                ]
            ]);
    });

    it('can create a new shed', function () {
        $shedData = [
            'farm_id' => $this->farm->id,
            'name' => 'New Shed',
            'capacity' => 1000,
            'type' => 'broiler',
            'description' => 'A new broiler shed',
        ];

        $response = $this->postJson('/api/v1/sheds', $shedData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Shed created successfully.',
                'shed' => [
                    'data' => [
                        'attributes' => [
                            'name' => 'New Shed',
                            'capacity' => 1000,
                            'type' => 'broiler',
                            'description' => 'A new broiler shed',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('sheds', [
            'name' => 'New Shed',
            'farm_id' => $this->farm->id,
            'capacity' => 1000,
        ]);
    });

    it('validates required fields when creating shed', function () {
        $response = $this->postJson('/api/v1/sheds', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['farm_id', 'name', 'capacity', 'type']);
    });

    it('validates shed type enum values', function () {
        $response = $this->postJson('/api/v1/sheds', [
            'farm_id' => $this->farm->id,
            'name' => 'Test Shed',
            'capacity' => 1000,
            'type' => 'invalid_type',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type']);
    });

    it('validates capacity minimum value', function () {
        $response = $this->postJson('/api/v1/sheds', [
            'farm_id' => $this->farm->id,
            'name' => 'Test Shed',
            'capacity' => 0,
            'type' => 'broiler',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['capacity']);
    });

    it('can show shed with detailed relationships', function () {
        $shed = Shed::factory()->create(['farm_id' => $this->farm->id]);
        $flock = Flock::factory()->create(['shed_id' => $shed->id]);
        $device = Device::factory()->create();
        $shed->devices()->attach($device->id);

        $response = $this->getJson("/api/v1/sheds/{$shed->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'capacity',
                        'type',
                        'description',
                        'flocks_count',
                        'devices_count',
                        'created_at',
                        'updated_at',
                        'farm' => [
                            'id',
                            'name',
                            'address',
                        ],
                        'flocks' => [
                            '*' => [
                                'id',
                                'name',
                                'start_date',
                                'end_date',
                                'chicken_count',
                                'status',
                            ]
                        ],
                        'devices' => [
                            '*' => [
                                'id',
                                'serial_no',
                                'firmware_version',
                                'capabilities',
                                'link_date',
                            ]
                        ]
                    ]
                ]
            ]);
    });

    it('can update shed', function () {
        $shed = Shed::factory()->create(['farm_id' => $this->farm->id]);

        $updateData = [
            'name' => 'Updated Shed Name',
            'capacity' => 1500,
            'type' => 'layer',
            'description' => 'Updated description',
        ];

        $response = $this->putJson("/api/v1/sheds/{$shed->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Shed updated successfully.',
                'shed' => [
                    'data' => [
                        'attributes' => [
                            'name' => 'Updated Shed Name',
                            'capacity' => 1500,
                            'type' => 'layer',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('sheds', [
            'id' => $shed->id,
            'name' => 'Updated Shed Name',
            'capacity' => 1500,
        ]);
    });

    it('can delete shed', function () {
        $shed = Shed::factory()->create(['farm_id' => $this->farm->id]);

        $response = $this->deleteJson("/api/v1/sheds/{$shed->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Shed deleted successfully.',
            ]);

        $this->assertDatabaseMissing('sheds', ['id' => $shed->id]);
    });

    it('returns 404 for non-existent shed', function () {
        $response = $this->getJson('/api/v1/sheds/99999');

        $response->assertStatus(404);
    });

    it('only shows sheds from farms owned by authenticated user', function () {
        $otherUser = User::factory()->create();
        $otherFarm = Farm::factory()->create(['owner_id' => $otherUser->id]);
        $otherShed = Shed::factory()->create(['farm_id' => $otherFarm->id]);
        $userShed = Shed::factory()->create(['farm_id' => $this->farm->id]);

        $response = $this->getJson('/api/v1/sheds');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $userShed->id);
    });

    it('prevents access to sheds from other users farms', function () {
        $otherUser = User::factory()->create();
        $otherFarm = Farm::factory()->create(['owner_id' => $otherUser->id]);
        $otherShed = Shed::factory()->create(['farm_id' => $otherFarm->id]);

        $response = $this->getJson("/api/v1/sheds/{$otherShed->id}");

        $response->assertStatus(404);
    });

    it('requires authentication for protected endpoints', function () {
        $this->actingAs($this->user)->get('/api/v1/sheds'); // Ensure we're authenticated first
        auth()->guard('web')->logout();

        $response = $this->getJson('/api/v1/sheds');

        $response->assertStatus(401);
    });
}); 