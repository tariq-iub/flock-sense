<?php

use App\Models\User;
use App\Models\Farm;
use App\Models\Shed;
use App\Models\Flock;
use App\Models\Breed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->farm = Farm::factory()->create(['owner_id' => $this->user->id]);
    $this->shed = Shed::factory()->create(['farm_id' => $this->farm->id]);
    $this->breed = Breed::factory()->create();
    Sanctum::actingAs($this->user);
});

describe('Flock API Endpoints', function () {
    it('can list flocks with query builder features', function () {
        $flock1 = Flock::factory()->create(['shed_id' => $this->shed->id, 'name' => 'Flock Alpha']);
        $flock2 = Flock::factory()->create(['shed_id' => $this->shed->id, 'name' => 'Flock Beta']);
        
        // Create flock for different user (should not appear in results)
        $otherUser = User::factory()->create();
        $otherFarm = Farm::factory()->create(['owner_id' => $otherUser->id]);
        $otherShed = Shed::factory()->create(['farm_id' => $otherFarm->id]);
        Flock::factory()->create(['shed_id' => $otherShed->id]);

        $response = $this->getJson('/api/v1/flocks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'start_date',
                            'end_date',
                            'chicken_count',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]
            ])
            ->assertJsonCount(2, 'data');
    });

    it('can filter flocks by name', function () {
        Flock::factory()->create(['shed_id' => $this->shed->id, 'name' => 'Alpha Flock']);
        Flock::factory()->create(['shed_id' => $this->shed->id, 'name' => 'Beta Flock']);

        $response = $this->getJson('/api/v1/flocks?filter[name]=Alpha');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    });

    it('can filter flocks by shed ID', function () {
        $shed2 = Shed::factory()->create(['farm_id' => $this->farm->id]);
        Flock::factory()->create(['shed_id' => $this->shed->id]);
        Flock::factory()->create(['shed_id' => $shed2->id]);

        $response = $this->getJson("/api/v1/flocks?filter[shed_id]={$this->shed->id}");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    });

    it('can sort flocks by start date', function () {
        Flock::factory()->create(['shed_id' => $this->shed->id, 'start_date' => '2024-01-15']);
        Flock::factory()->create(['shed_id' => $this->shed->id, 'start_date' => '2024-01-01']);

        $response = $this->getJson('/api/v1/flocks?sort=start_date');

        $response->assertStatus(200);
        $data = $response->json('data');
        expect($data[0]['attributes']['start_date'])->toBe('2024-01-01');
    });

    it('can include shed relationship', function () {
        $flock = Flock::factory()->create(['shed_id' => $this->shed->id]);

        $response = $this->getJson('/api/v1/flocks?include=shed');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'attributes' => [
                            'shed' => [
                                'id',
                                'name',
                                'capacity',
                                'type',
                            ]
                        ]
                    ]
                ]
            ]);
    });

    it('can include breed relationship', function () {
        $flock = Flock::factory()->create(['shed_id' => $this->shed->id, 'breed_id' => $this->breed->id]);

        $response = $this->getJson('/api/v1/flocks?include=breed');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'attributes' => [
                            'breed' => [
                                'id',
                                'name',
                            ]
                        ]
                    ]
                ]
            ]);
    });

    it('can create a new flock', function () {
        $flockData = [
            'name' => 'New Flock',
            'shed_id' => $this->shed->id,
            'breed_id' => $this->breed->id,
            'start_date' => '2024-01-01',
            'end_date' => '2024-06-01',
            'chicken_count' => 1000,
        ];

        $response = $this->postJson('/api/v1/flocks', $flockData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Flock created successfully.',
                'flock' => [
                    'data' => [
                        'attributes' => [
                            'name' => 'New Flock',
                            'start_date' => '2024-01-01',
                            'end_date' => '2024-06-01',
                            'chicken_count' => 1000,
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('flocks', [
            'name' => 'New Flock',
            'shed_id' => $this->shed->id,
            'breed_id' => $this->breed->id,
            'chicken_count' => 1000,
        ]);
    });

    it('validates required fields when creating flock', function () {
        $response = $this->postJson('/api/v1/flocks', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'shed_id', 'breed_id', 'start_date', 'chicken_count']);
    });

    it('validates shed exists when creating flock', function () {
        $response = $this->postJson('/api/v1/flocks', [
            'name' => 'Test Flock',
            'shed_id' => 99999,
            'breed_id' => $this->breed->id,
            'start_date' => '2024-01-01',
            'chicken_count' => 1000,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['shed_id']);
    });

    it('validates breed exists when creating flock', function () {
        $response = $this->postJson('/api/v1/flocks', [
            'name' => 'Test Flock',
            'shed_id' => $this->shed->id,
            'breed_id' => 99999,
            'start_date' => '2024-01-01',
            'chicken_count' => 1000,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['breed_id']);
    });

    it('validates chicken count minimum value', function () {
        $response = $this->postJson('/api/v1/flocks', [
            'name' => 'Test Flock',
            'shed_id' => $this->shed->id,
            'breed_id' => $this->breed->id,
            'start_date' => '2024-01-01',
            'chicken_count' => 0,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['chicken_count']);
    });

    it('validates start date format', function () {
        $response = $this->postJson('/api/v1/flocks', [
            'name' => 'Test Flock',
            'shed_id' => $this->shed->id,
            'breed_id' => $this->breed->id,
            'start_date' => 'invalid-date',
            'chicken_count' => 1000,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['start_date']);
    });

    it('can show flock with detailed relationships', function () {
        $flock = Flock::factory()->create([
            'shed_id' => $this->shed->id,
            'breed_id' => $this->breed->id
        ]);

        $response = $this->getJson("/api/v1/flocks/{$flock->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'start_date',
                        'end_date',
                        'chicken_count',
                        'created_at',
                        'updated_at',
                        'shed' => [
                            'id',
                            'name',
                            'capacity',
                            'type',
                        ],
                        'breed' => [
                            'id',
                            'name',
                        ]
                    ]
                ]
            ]);
    });

    it('can update flock', function () {
        $flock = Flock::factory()->create(['shed_id' => $this->shed->id]);

        $updateData = [
            'name' => 'Updated Flock Name',
            'end_date' => '2024-12-31',
            'chicken_count' => 1500,
        ];

        $response = $this->putJson("/api/v1/flocks/{$flock->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Flock updated successfully.',
                'flock' => [
                    'data' => [
                        'attributes' => [
                            'name' => 'Updated Flock Name',
                            'end_date' => '2024-12-31',
                            'chicken_count' => 1500,
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('flocks', [
            'id' => $flock->id,
            'name' => 'Updated Flock Name',
            'chicken_count' => 1500,
        ]);
    });

    it('can delete flock', function () {
        $flock = Flock::factory()->create(['shed_id' => $this->shed->id]);

        $response = $this->deleteJson("/api/v1/flocks/{$flock->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Flock deleted successfully.',
            ]);

        $this->assertDatabaseMissing('flocks', ['id' => $flock->id]);
    });

    it('returns 404 for non-existent flock', function () {
        $response = $this->getJson('/api/v1/flocks/99999');

        $response->assertStatus(404);
    });

    it('only shows flocks from sheds owned by authenticated user', function () {
        $otherUser = User::factory()->create();
        $otherFarm = Farm::factory()->create(['owner_id' => $otherUser->id]);
        $otherShed = Shed::factory()->create(['farm_id' => $otherFarm->id]);
        $otherFlock = Flock::factory()->create(['shed_id' => $otherShed->id]);
        $userFlock = Flock::factory()->create(['shed_id' => $this->shed->id]);

        $response = $this->getJson('/api/v1/flocks');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $userFlock->id);
    });

    it('prevents access to flocks from other users sheds', function () {
        $otherUser = User::factory()->create();
        $otherFarm = Farm::factory()->create(['owner_id' => $otherUser->id]);
        $otherShed = Shed::factory()->create(['farm_id' => $otherFarm->id]);
        $otherFlock = Flock::factory()->create(['shed_id' => $otherShed->id]);

        $response = $this->getJson("/api/v1/flocks/{$otherFlock->id}");

        $response->assertStatus(404);
    });

    it('requires authentication for protected endpoints', function () {
        $this->actingAs($this->user)->get('/api/v1/flocks'); // Ensure we're authenticated first
        auth()->guard('web')->logout();

        $response = $this->getJson('/api/v1/flocks');

        $response->assertStatus(401);
    });
}); 