<?php

use App\Models\Farm;
use App\Models\User;
use App\Models\Shed;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Farm Model', function () {
    it('can create a farm', function () {
        $user = User::factory()->create();
        $farm = Farm::factory()->create([
            'name' => 'Test Farm',
            'address' => '123 Farm Street',
            'owner_id' => $user->id,
        ]);

        expect($farm->name)->toBe('Test Farm');
        expect($farm->address)->toBe('123 Farm Street');
        expect($farm->owner_id)->toBe($user->id);
    });

    it('belongs to an owner', function () {
        $user = User::factory()->create();
        $farm = Farm::factory()->create(['owner_id' => $user->id]);

        expect($farm->owner->id)->toBe($user->id);
        expect($farm->owner->name)->toBe($user->name);
    });

    it('has many sheds', function () {
        $farm = Farm::factory()->create();
        $sheds = Shed::factory()->count(3)->create(['farm_id' => $farm->id]);

        expect($farm->sheds)->toHaveCount(3);
        expect($farm->sheds->pluck('id')->toArray())->toBe($sheds->pluck('id')->toArray());
    });

    it('can have no sheds', function () {
        $farm = Farm::factory()->create();

        expect($farm->sheds)->toHaveCount(0);
    });

    it('has fillable attributes', function () {
        $user = User::factory()->create();
        $farm = Farm::factory()->create([
            'name' => 'Test Farm',
            'address' => 'Test Address',
            'owner_id' => $user->id,
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ]);

        expect($farm->name)->toBe('Test Farm');
        expect($farm->address)->toBe('Test Address');
        expect($farm->owner_id)->toBe($user->id);
        expect($farm->latitude)->toBe(40.7128);
        expect($farm->longitude)->toBe(-74.0060);
    });

    it('casts latitude and longitude as floats', function () {
        $farm = Farm::factory()->create([
            'latitude' => '40.7128',
            'longitude' => '-74.0060',
        ]);

        expect($farm->latitude)->toBe(40.7128);
        expect($farm->longitude)->toBe(-74.0060);
        expect($farm->latitude)->toBeFloat();
        expect($farm->longitude)->toBeFloat();
    });

    it('casts timestamps correctly', function () {
        $farm = Farm::factory()->create();

        expect($farm->created_at)->toBeInstanceOf(\Carbon\Carbon::class);
        expect($farm->updated_at)->toBeInstanceOf(\Carbon\Carbon::class);
    });

    it('can be soft deleted', function () {
        $farm = Farm::factory()->create();
        $farmId = $farm->id;

        $farm->delete();

        expect(Farm::find($farmId))->toBeNull();
        expect(Farm::withTrashed()->find($farmId))->not->toBeNull();
    });

    it('can be restored after soft delete', function () {
        $farm = Farm::factory()->create();
        $farmId = $farm->id;

        $farm->delete();
        expect(Farm::find($farmId))->toBeNull();

        $farm->restore();
        expect(Farm::find($farmId))->not->toBeNull();
    });

    it('can be permanently deleted', function () {
        $farm = Farm::factory()->create();
        $farmId = $farm->id;

        $farm->forceDelete();

        expect(Farm::find($farmId))->toBeNull();
        expect(Farm::withTrashed()->find($farmId))->toBeNull();
    });

    it('has media relationship', function () {
        $farm = Farm::factory()->create();
        
        // Test that media relationship exists (assuming HasMedia trait is used)
        expect($farm->media)->toBeDefined();
    });

    it('can scope by owner', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        Farm::factory()->count(2)->create(['owner_id' => $user1->id]);
        Farm::factory()->count(3)->create(['owner_id' => $user2->id]);

        $user1Farms = Farm::where('owner_id', $user1->id)->get();
        $user2Farms = Farm::where('owner_id', $user2->id)->get();

        expect($user1Farms)->toHaveCount(2);
        expect($user2Farms)->toHaveCount(3);
    });

    it('can get sheds count', function () {
        $farm = Farm::factory()->create();
        Shed::factory()->count(5)->create(['farm_id' => $farm->id]);

        expect($farm->sheds()->count())->toBe(5);
    });

    it('can get total birds count through sheds', function () {
        $farm = Farm::factory()->create();
        $shed1 = Shed::factory()->create(['farm_id' => $farm->id]);
        $shed2 = Shed::factory()->create(['farm_id' => $farm->id]);
        
        // Create flocks for each shed
        \App\Models\Flock::factory()->create(['shed_id' => $shed1->id, 'chicken_count' => 1000]);
        \App\Models\Flock::factory()->create(['shed_id' => $shed2->id, 'chicken_count' => 1500]);

        $totalBirds = $farm->sheds->sum(function ($shed) {
            return $shed->flocks->sum('chicken_count');
        });

        expect($totalBirds)->toBe(2500);
    });
}); 