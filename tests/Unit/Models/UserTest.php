<?php

use App\Models\User;
use App\Models\Farm;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('User Model', function () {
    it('can create a user', function () {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        expect($user->name)->toBe('John Doe');
        expect($user->email)->toBe('john@example.com');
    });

    it('has farms relationship', function () {
        $user = User::factory()->create();
        $farm = Farm::factory()->create(['owner_id' => $user->id]);

        expect($user->farms)->toHaveCount(1);
        expect($user->farms->first()->id)->toBe($farm->id);
    });

    it('can have multiple farms', function () {
        $user = User::factory()->create();
        Farm::factory()->count(3)->create(['owner_id' => $user->id]);

        expect($user->farms)->toHaveCount(3);
    });

    it('can get role names', function () {
        $user = User::factory()->create();
        
        // Assuming the user has some roles assigned
        $roleNames = $user->getRoleNames();
        
        expect($roleNames)->toBeCollection();
    });

    it('can check if email is verified', function () {
        $user = User::factory()->create(['email_verified_at' => null]);
        expect($user->email_verified_at)->toBeNull();

        $user->email_verified_at = now();
        expect($user->email_verified_at)->not->toBeNull();
    });

    it('has media relationship', function () {
        $user = User::factory()->create();
        
        // Test that media relationship exists (assuming HasMedia trait is used)
        expect($user->media)->toBeDefined();
    });

    it('can be soft deleted', function () {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        expect(User::find($userId))->toBeNull();
        expect(User::withTrashed()->find($userId))->not->toBeNull();
    });

    it('can be restored after soft delete', function () {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();
        expect(User::find($userId))->toBeNull();

        $user->restore();
        expect(User::find($userId))->not->toBeNull();
    });

    it('can be permanently deleted', function () {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->forceDelete();

        expect(User::find($userId))->toBeNull();
        expect(User::withTrashed()->find($userId))->toBeNull();
    });

    it('has fillable attributes', function () {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+1234567890',
        ]);

        expect($user->name)->toBe('Test User');
        expect($user->email)->toBe('test@example.com');
        expect($user->phone)->toBe('+1234567890');
    });

    it('has hidden attributes', function () {
        $user = User::factory()->create();
        $userArray = $user->toArray();

        expect($userArray)->not->toHaveKey('password');
        expect($userArray)->not->toHaveKey('remember_token');
    });

    it('casts attributes correctly', function () {
        $user = User::factory()->create([
            'email_verified_at' => '2024-01-01 12:00:00',
        ]);

        expect($user->email_verified_at)->toBeInstanceOf(\Carbon\Carbon::class);
    });
}); 