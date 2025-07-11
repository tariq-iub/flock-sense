<?php

use App\Models\User;
use App\Models\Breed;
use App\Models\Flock;
use App\Models\Shed;
use App\Models\Farm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);
});

describe('Breed API Endpoints', function () {
    it('can list breeds with query builder features', function () {
        $breed1 = Breed::factory()->create(['name' => 'Breed Alpha', 'category' => 'broiler']);
        $breed2 = Breed::factory()->create(['name' => 'Breed Beta', 'category' => 'layer']);

        $response = $this->getJson('/api/v1/breeds');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'flocks_count',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]
            ])
            ->assertJsonCount(2, 'data');
    });

    it('can filter breeds by name', function () {
        Breed::factory()->create(['name' => 'Alpha Breed']);
        Breed::factory()->create(['name' => 'Beta Breed']);

        $response = $this->getJson('/api/v1/breeds?filter[name]=Alpha');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    });

    it('can filter breeds by category', function () {
        Breed::factory()->create(['category' => 'broiler']);
        Breed::factory()->create(['category' => 'layer']);

        $response = $this->getJson('/api/v1/breeds?filter[category]=broiler');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    });

    it('can sort breeds by name', function () {
        Breed::factory()->create(['name' => 'Zebra Breed']);
        Breed::factory()->create(['name' => 'Alpha Breed']);

        $response = $this->getJson('/api/v1/breeds?sort=name');

        $response->assertStatus(200);
        $data = $response->json('data');
        expect($data[0]['attributes']['name'])->toBe('Alpha Breed');
    });

    it('can create a new breed', function () {
        $breedData = [
            'name' => 'New Breed',
            'category' => 'broiler',
        ];

        $response = $this->postJson('/api/v1/breeds', $breedData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Breed created successfully.',
                'breed' => [
                    'data' => [
                        'attributes' => [
                            'name' => 'New Breed',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('breeds', [
            'name' => 'New Breed',
            'category' => 'broiler',
        ]);
    });

    it('validates required fields when creating breed', function () {
        $response = $this->postJson('/api/v1/breeds', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'category']);
    });

    it('validates breed category enum values', function () {
        $response = $this->postJson('/api/v1/breeds', [
            'name' => 'Test Breed',
            'category' => 'invalid_category',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['category']);
    });

    it('validates name maximum length', function () {
        $response = $this->postJson('/api/v1/breeds', [
            'name' => str_repeat('a', 256), // Exceeds 255 character limit
            'category' => 'broiler',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    });

    it('can show breed with detailed relationships', function () {
        $breed = Breed::factory()->create();
        $farm = Farm::factory()->create(['owner_id' => $this->user->id]);
        $shed = Shed::factory()->create(['farm_id' => $farm->id]);
        $flock = Flock::factory()->create(['shed_id' => $shed->id, 'breed_id' => $breed->id]);

        $response = $this->getJson("/api/v1/breeds/{$breed->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'flocks_count',
                        'created_at',
                        'updated_at',
                        'flocks' => [
                            '*' => [
                                'id',
                                'name',
                                'start_date',
                                'end_date',
                                'chicken_count',
                                'status',
                            ]
                        ]
                    ]
                ]
            ]);
    });

    it('can update breed', function () {
        $breed = Breed::factory()->create();

        $updateData = [
            'name' => 'Updated Breed Name',
            'category' => 'layer',
        ];

        $response = $this->putJson("/api/v1/breeds/{$breed->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Breed updated successfully.',
                'breed' => [
                    'data' => [
                        'attributes' => [
                            'name' => 'Updated Breed Name',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('breeds', [
            'id' => $breed->id,
            'name' => 'Updated Breed Name',
            'category' => 'layer',
        ]);
    });

    it('can delete breed', function () {
        $breed = Breed::factory()->create();

        $response = $this->deleteJson("/api/v1/breeds/{$breed->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Breed deleted successfully.',
            ]);

        $this->assertDatabaseMissing('breeds', ['id' => $breed->id]);
    });

    it('returns 404 for non-existent breed', function () {
        $response = $this->getJson('/api/v1/breeds/99999');

        $response->assertStatus(404);
    });

    it('requires authentication for protected endpoints', function () {
        $this->actingAs($this->user)->get('/api/v1/breeds'); // Ensure we're authenticated first
        auth()->guard('web')->logout();

        $response = $this->getJson('/api/v1/breeds');

        $response->assertStatus(401);
    });

    it('shows correct flocks count for breed', function () {
        $breed = Breed::factory()->create();
        $farm = Farm::factory()->create(['owner_id' => $this->user->id]);
        $shed = Shed::factory()->create(['farm_id' => $farm->id]);
        
        // Create 3 flocks for this breed
        Flock::factory()->count(3)->create(['shed_id' => $shed->id, 'breed_id' => $breed->id]);

        $response = $this->getJson('/api/v1/breeds');

        $response->assertStatus(200);
        $data = $response->json('data');
        $breedData = collect($data)->firstWhere('id', $breed->id);
        expect($breedData['attributes']['flocks_count'])->toBe(3);
    });

    it('includes farm information in breed relationships', function () {
        $breed = Breed::factory()->create();
        $farm = Farm::factory()->create(['owner_id' => $this->user->id]);
        $shed = Shed::factory()->create(['farm_id' => $farm->id]);
        $flock = Flock::factory()->create(['shed_id' => $shed->id, 'breed_id' => $breed->id]);

        $response = $this->getJson("/api/v1/breeds/{$breed->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'attributes' => [
                        'flocks' => [
                            '*' => [
                                'shed' => [
                                    'farm' => [
                                        'id',
                                        'name',
                                        'address',
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    });
}); 