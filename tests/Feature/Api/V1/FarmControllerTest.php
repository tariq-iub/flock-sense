<?php

use App\Models\User;
use App\Models\Farm;
use App\Models\Shed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);
});

describe('Farm API Endpoints', function () {
    it('can list farms with query builder features', function () {
        $farm1 = Farm::factory()->create(['owner_id' => $this->user->id, 'name' => 'Farm Alpha']);
        $farm2 = Farm::factory()->create(['owner_id' => $this->user->id, 'name' => 'Farm Beta']);
        
        // Create farm for different user (should not appear in results)
        Farm::factory()->create(['owner_id' => User::factory()->create()->id]);

        $response = $this->getJson('/api/v1/farms');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'address',
                            'latitude',
                            'longitude',
                            'sheds_count',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]
            ])
            ->assertJsonCount(2, 'data');
    });

    it('can filter farms by name', function () {
        Farm::factory()->create(['owner_id' => $this->user->id, 'name' => 'Alpha Farm']);
        Farm::factory()->create(['owner_id' => $this->user->id, 'name' => 'Beta Farm']);

        $response = $this->getJson('/api/v1/farms?filter[name]=Alpha');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    });

    it('can sort farms by name', function () {
        Farm::factory()->create(['owner_id' => $this->user->id, 'name' => 'Zebra Farm']);
        Farm::factory()->create(['owner_id' => $this->user->id, 'name' => 'Alpha Farm']);

        $response = $this->getJson('/api/v1/farms?sort=name');

        $response->assertStatus(200);
        $data = $response->json('data');
        expect($data[0]['attributes']['name'])->toBe('Alpha Farm');
    });

    it('can include sheds relationship', function () {
        $farm = Farm::factory()->create(['owner_id' => $this->user->id]);
        Shed::factory()->count(2)->create(['farm_id' => $farm->id]);

        $response = $this->getJson('/api/v1/farms?include=sheds');

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
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    });

    it('can create a new farm', function () {
        $farmData = [
            'name' => 'New Farm',
            'address' => '123 Farm Street, Farm City',
            'owner_id' => $this->user->id,
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ];

        $response = $this->postJson('/api/v1/farms', $farmData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Farm created successfully.',
                'farm' => [
                    'data' => [
                        'attributes' => [
                            'name' => 'New Farm',
                            'address' => '123 Farm Street, Farm City',
                            'latitude' => 40.7128,
                            'longitude' => -74.0060,
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('farms', [
            'name' => 'New Farm',
            'owner_id' => $this->user->id,
        ]);
    });

    it('validates required fields when creating farm', function () {
        $response = $this->postJson('/api/v1/farms', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'address', 'owner_id']);
    });

    it('validates latitude and longitude ranges', function () {
        $response = $this->postJson('/api/v1/farms', [
            'name' => 'Test Farm',
            'address' => 'Test Address',
            'owner_id' => $this->user->id,
            'latitude' => 100, // Invalid latitude
            'longitude' => 200, // Invalid longitude
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['latitude', 'longitude']);
    });

    it('can show farm with detailed relationships', function () {
        $farm = Farm::factory()->create(['owner_id' => $this->user->id]);
        $shed = Shed::factory()->create(['farm_id' => $farm->id]);

        $response = $this->getJson("/api/v1/farms/{$farm->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'address',
                        'latitude',
                        'longitude',
                        'sheds_count',
                        'created_at',
                        'updated_at',
                        'owner' => [
                            'id',
                            'name',
                            'email',
                        ]
                    ]
                ]
            ]);
    });

    it('can update farm', function () {
        $farm = Farm::factory()->create(['owner_id' => $this->user->id]);

        $updateData = [
            'name' => 'Updated Farm Name',
            'address' => 'Updated Address',
            'latitude' => 35.6762,
            'longitude' => 139.6503,
        ];

        $response = $this->putJson("/api/v1/farms/{$farm->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Farm updated successfully.',
                'farm' => [
                    'data' => [
                        'attributes' => [
                            'name' => 'Updated Farm Name',
                            'address' => 'Updated Address',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('farms', [
            'id' => $farm->id,
            'name' => 'Updated Farm Name',
            'address' => 'Updated Address',
        ]);
    });

    it('can delete farm', function () {
        $farm = Farm::factory()->create(['owner_id' => $this->user->id]);

        $response = $this->deleteJson("/api/v1/farms/{$farm->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Farm deleted successfully.',
            ]);

        $this->assertDatabaseMissing('farms', ['id' => $farm->id]);
    });

    it('returns 404 for non-existent farm', function () {
        $response = $this->getJson('/api/v1/farms/99999');

        $response->assertStatus(404);
    });

    it('only shows farms owned by authenticated user', function () {
        $otherUser = User::factory()->create();
        $otherUserFarm = Farm::factory()->create(['owner_id' => $otherUser->id]);
        $userFarm = Farm::factory()->create(['owner_id' => $this->user->id]);

        $response = $this->getJson('/api/v1/farms');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $userFarm->id);
    });

    it('prevents access to farms owned by other users', function () {
        $otherUser = User::factory()->create();
        $otherUserFarm = Farm::factory()->create(['owner_id' => $otherUser->id]);

        $response = $this->getJson("/api/v1/farms/{$otherUserFarm->id}");

        $response->assertStatus(404);
    });

    it('requires authentication for protected endpoints', function () {
        $this->actingAs($this->user)->get('/api/v1/farms'); // Ensure we're authenticated first
        auth()->guard('web')->logout();

        $response = $this->getJson('/api/v1/farms');

        $response->assertStatus(401);
    });
}); 