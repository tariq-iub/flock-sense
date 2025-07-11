<?php

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Farm;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('UserResource', function () {
    it('transforms user data correctly', function () {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
        ]);

        $resource = new UserResource($user);
        $data = $resource->toArray(request());

        expect($data['data']['type'])->toBe('users');
        expect($data['data']['id'])->toBe($user->id);
        expect($data['data']['attributes']['name'])->toBe('John Doe');
        expect($data['data']['attributes']['email'])->toBe('john@example.com');
        expect($data['data']['attributes']['phone'])->toBe('+1234567890');
    });

    it('includes farms count', function () {
        $user = User::factory()->create();
        Farm::factory()->count(3)->create(['owner_id' => $user->id]);

        $resource = new UserResource($user);
        $data = $resource->toArray(request());

        expect($data['data']['attributes']['farms_count'])->toBe(3);
    });

    it('includes farms relationship when requested', function () {
        $user = User::factory()->create();
        $farm = Farm::factory()->create(['owner_id' => $user->id]);

        $request = request();
        $request->merge(['include' => 'farms']);

        $resource = new UserResource($user);
        $data = $resource->toArray($request);

        expect($data['data']['attributes'])->toHaveKey('farms');
        expect($data['data']['attributes']['farms'])->toHaveCount(1);
        expect($data['data']['attributes']['farms'][0]['id'])->toBe($farm->id);
    });

    it('does not include farms relationship when not requested', function () {
        $user = User::factory()->create();
        Farm::factory()->create(['owner_id' => $user->id]);

        $resource = new UserResource($user);
        $data = $resource->toArray(request());

        expect($data['data']['attributes'])->not->toHaveKey('farms');
    });

    it('formats dates correctly', function () {
        $user = User::factory()->create([
            'created_at' => '2024-01-01 12:00:00',
            'updated_at' => '2024-01-02 12:00:00',
        ]);

        $resource = new UserResource($user);
        $data = $resource->toArray(request());

        expect($data['data']['attributes']['create_at'])->toBe('2024-01-01T12:00:00.000000Z');
    });

    it('handles email verification status', function () {
        $user = User::factory()->create(['email_verified_at' => null]);

        $resource = new UserResource($user);
        $data = $resource->toArray(request());

        expect($data['data']['attributes']['email_verified'])->toBeFalse();

        $user->email_verified_at = now();
        $resource = new UserResource($user);
        $data = $resource->toArray(request());

        expect($data['data']['attributes']['email_verified'])->toBeTrue();
    });

    it('includes roles information', function () {
        $user = User::factory()->create();

        $resource = new UserResource($user);
        $data = $resource->toArray(request());

        expect($data['data']['attributes'])->toHaveKey('roles');
        expect($data['data']['attributes']['roles'])->toBeArray();
    });

    it('handles null phone number', function () {
        $user = User::factory()->create(['phone' => null]);

        $resource = new UserResource($user);
        $data = $resource->toArray(request());

        expect($data['data']['attributes']['phone'])->toBeNull();
    });

    it('handles null avatar', function () {
        $user = User::factory()->create(['avatar' => null]);

        $resource = new UserResource($user);
        $data = $resource->toArray(request());

        expect($data['data']['attributes']['avatar'])->toBeNull();
    });

    it('includes sheds and birds count for detailed view', function () {
        $user = User::factory()->create();
        $farm = Farm::factory()->create(['owner_id' => $user->id]);
        $shed = \App\Models\Shed::factory()->create(['farm_id' => $farm->id]);
        \App\Models\Flock::factory()->create(['shed_id' => $shed->id, 'chicken_count' => 1000]);

        $resource = new UserResource($user);
        $data = $resource->toArray(request());

        expect($data['data']['attributes'])->toHaveKey('sheds_count');
        expect($data['data']['attributes'])->toHaveKey('birds_count');
        expect($data['data']['attributes']['birds_count'])->toBe(1000);
    });

    it('handles empty relationships gracefully', function () {
        $user = User::factory()->create();

        $resource = new UserResource($user);
        $data = $resource->toArray(request());

        expect($data['data']['attributes']['farms_count'])->toBe(0);
        expect($data['data']['attributes']['sheds_count'])->toBe(0);
        expect($data['data']['attributes']['birds_count'])->toBe(0);
    });

    it('maintains JSON:API structure', function () {
        $user = User::factory()->create();

        $resource = new UserResource($user);
        $data = $resource->toArray(request());

        expect($data)->toHaveKey('data');
        expect($data['data'])->toHaveKey('type');
        expect($data['data'])->toHaveKey('id');
        expect($data['data'])->toHaveKey('attributes');
        expect($data['data']['type'])->toBe('users');
    });

    it('can be serialized to JSON', function () {
        $user = User::factory()->create();

        $resource = new UserResource($user);
        $json = $resource->toJson();

        expect($json)->toBeString();
        expect(json_decode($json, true))->toHaveKey('data');
    });

    it('handles collection transformation', function () {
        $users = User::factory()->count(3)->create();

        $collection = UserResource::collection($users);
        $data = $collection->toArray(request());

        expect($data['data'])->toHaveCount(3);
        expect($data['data'][0]['type'])->toBe('users');
        expect($data['data'][1]['type'])->toBe('users');
        expect($data['data'][2]['type'])->toBe('users');
    });
}); 