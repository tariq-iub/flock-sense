<?php

use App\Models\User;
use App\Models\Farm;
use App\Models\Shed;
use App\Models\Flock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);
});

describe('User API Endpoints', function () {
    it('can list users with query builder features', function () {
        // Create additional users
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'email',
                            'phone',
                            'avatar',
                            'roles',
                            'farms_count',
                            'email_verified',
                            'create_at',
                        ]
                    ]
                ]
            ]);
    });

    it('can filter users by name', function () {
        User::factory()->create(['name' => 'John Doe']);
        User::factory()->create(['name' => 'Jane Smith']);

        $response = $this->getJson('/api/v1/users?filter[name]=John');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    });

    it('can sort users by name', function () {
        User::factory()->create(['name' => 'Zebra']);
        User::factory()->create(['name' => 'Alpha']);

        $response = $this->getJson('/api/v1/users?sort=name');

        $response->assertStatus(200);
        $data = $response->json('data');
        expect($data[0]['attributes']['name'])->toBe('Alpha');
    });

    it('can include farms relationship', function () {
        $farm = Farm::factory()->create(['owner_id' => $this->user->id]);

        $response = $this->getJson('/api/v1/users?include=farms');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'attributes' => [
                            'farms' => [
                                '*' => [
                                    'id',
                                    'name',
                                    'address',
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    });

    it('can show user with detailed relationships', function () {
        $farm = Farm::factory()->create(['owner_id' => $this->user->id]);
        $shed = Shed::factory()->create(['farm_id' => $farm->id]);
        $flock = Flock::factory()->create(['shed_id' => $shed->id]);

        $response = $this->getJson("/api/v1/users/{$this->user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'email',
                        'phone',
                        'avatar',
                        'roles',
                        'farms_count',
                        'sheds_count',
                        'birds_count',
                        'email_verified',
                        'create_at',
                    ]
                ]
            ]);
    });

    it('can update user profile', function () {
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '+1234567890',
        ];

        $response = $this->putJson("/api/v1/users/{$this->user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User updated successfully.',
                'user' => [
                    'data' => [
                        'attributes' => [
                            'name' => 'Updated Name',
                            'email' => 'updated@example.com',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    });

    it('can update user password', function () {
        $updateData = [
            'password' => 'newpassword123',
        ];

        $response = $this->putJson("/api/v1/users/{$this->user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User updated successfully.',
            ]);

        // Verify password was hashed
        $this->user->refresh();
        expect(Hash::check('newpassword123', $this->user->password))->toBeTrue();
    });

    it('validates unique email on update', function () {
        $otherUser = User::factory()->create(['email' => 'other@example.com']);

        $response = $this->putJson("/api/v1/users/{$this->user->id}", [
            'email' => 'other@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    });

    it('validates unique phone on update', function () {
        $otherUser = User::factory()->create(['phone' => '+1234567890']);

        $response = $this->putJson("/api/v1/users/{$this->user->id}", [
            'phone' => '+1234567890',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);
    });

    it('can delete user', function () {
        $response = $this->deleteJson("/api/v1/users/{$this->user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User deleted successfully.',
            ]);

        $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
    });

    it('returns 404 for non-existent user', function () {
        $response = $this->getJson('/api/v1/users/99999');

        $response->assertStatus(404);
    });

    it('requires authentication for protected endpoints', function () {
        $this->actingAs($this->user)->get('/api/v1/users'); // Ensure we're authenticated first
        auth()->guard('web')->logout();

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(401);
    });
}); 