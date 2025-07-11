<?php

use App\Models\Device;
use App\Models\Shed;
use App\Models\DeviceAppliance;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Device Model', function () {
    it('can create a device', function () {
        $device = Device::factory()->create([
            'serial_no' => 'DEV001',
            'firmware_version' => 'v1.0.0',
            'capabilities' => ['temperature', 'humidity'],
        ]);

        expect($device->serial_no)->toBe('DEV001');
        expect($device->firmware_version)->toBe('v1.0.0');
        expect($device->capabilities)->toBe(['temperature', 'humidity']);
    });

    it('has many device appliances', function () {
        $device = Device::factory()->create();
        $appliances = DeviceAppliance::factory()->count(3)->create(['device_id' => $device->id]);

        expect($device->appliances)->toHaveCount(3);
        expect($device->appliances->pluck('id')->toArray())->toBe($appliances->pluck('id')->toArray());
    });

    it('belongs to many sheds', function () {
        $device = Device::factory()->create();
        $sheds = Shed::factory()->count(2)->create();

        $device->sheds()->attach($sheds->pluck('id')->toArray());

        expect($device->sheds)->toHaveCount(2);
        expect($device->sheds->pluck('id')->toArray())->toBe($sheds->pluck('id')->toArray());
    });

    it('can be detached from sheds', function () {
        $device = Device::factory()->create();
        $shed = Shed::factory()->create();

        $device->sheds()->attach($shed->id);
        expect($device->sheds)->toHaveCount(1);

        $device->sheds()->detach($shed->id);
        $device->load('sheds');
        expect($device->sheds)->toHaveCount(0);
    });

    it('has fillable attributes', function () {
        $device = Device::factory()->create([
            'serial_no' => 'TEST001',
            'firmware_version' => 'v2.0.0',
            'capabilities' => ['temperature', 'humidity', 'co2'],
        ]);

        expect($device->serial_no)->toBe('TEST001');
        expect($device->firmware_version)->toBe('v2.0.0');
        expect($device->capabilities)->toBe(['temperature', 'humidity', 'co2']);
    });

    it('casts capabilities as array', function () {
        $device = Device::factory()->create([
            'capabilities' => '["temperature", "humidity"]',
        ]);

        expect($device->capabilities)->toBe(['temperature', 'humidity']);
        expect($device->capabilities)->toBeArray();
    });

    it('casts timestamps correctly', function () {
        $device = Device::factory()->create();

        expect($device->created_at)->toBeInstanceOf(\Carbon\Carbon::class);
        expect($device->updated_at)->toBeInstanceOf(\Carbon\Carbon::class);
    });

    it('can be soft deleted', function () {
        $device = Device::factory()->create();
        $deviceId = $device->id;

        $device->delete();

        expect(Device::find($deviceId))->toBeNull();
        expect(Device::withTrashed()->find($deviceId))->not->toBeNull();
    });

    it('can be restored after soft delete', function () {
        $device = Device::factory()->create();
        $deviceId = $device->id;

        $device->delete();
        expect(Device::find($deviceId))->toBeNull();

        $device->restore();
        expect(Device::find($deviceId))->not->toBeNull();
    });

    it('can be permanently deleted', function () {
        $device = Device::factory()->create();
        $deviceId = $device->id;

        $device->forceDelete();

        expect(Device::find($deviceId))->toBeNull();
        expect(Device::withTrashed()->find($deviceId))->toBeNull();
    });

    it('has unique serial number', function () {
        Device::factory()->create(['serial_no' => 'DEV001']);

        expect(function () {
            Device::factory()->create(['serial_no' => 'DEV001']);
        })->toThrow(\Illuminate\Database\QueryException::class);
    });

    it('can get appliances count', function () {
        $device = Device::factory()->create();
        DeviceAppliance::factory()->count(5)->create(['device_id' => $device->id]);

        expect($device->appliances()->count())->toBe(5);
    });

    it('can get sheds count', function () {
        $device = Device::factory()->create();
        $sheds = Shed::factory()->count(3)->create();
        $device->sheds()->attach($sheds->pluck('id')->toArray());

        expect($device->sheds()->count())->toBe(3);
    });

    it('can check if device has specific capability', function () {
        $device = Device::factory()->create([
            'capabilities' => ['temperature', 'humidity', 'co2'],
        ]);

        expect(in_array('temperature', $device->capabilities))->toBeTrue();
        expect(in_array('nh3', $device->capabilities))->toBeFalse();
    });

    it('can get active appliances', function () {
        $device = Device::factory()->create();
        DeviceAppliance::factory()->create(['device_id' => $device->id, 'status' => true]);
        DeviceAppliance::factory()->create(['device_id' => $device->id, 'status' => false]);

        $activeAppliances = $device->appliances()->where('status', true)->get();
        expect($activeAppliances)->toHaveCount(1);
    });

    it('can get appliances by type', function () {
        $device = Device::factory()->create();
        DeviceAppliance::factory()->create(['device_id' => $device->id, 'type' => 'fan']);
        DeviceAppliance::factory()->create(['device_id' => $device->id, 'type' => 'light']);
        DeviceAppliance::factory()->create(['device_id' => $device->id, 'type' => 'fan']);

        $fanAppliances = $device->appliances()->where('type', 'fan')->get();
        expect($fanAppliances)->toHaveCount(2);
    });

    it('can get latest firmware version', function () {
        $device1 = Device::factory()->create(['firmware_version' => 'v1.0.0']);
        $device2 = Device::factory()->create(['firmware_version' => 'v2.0.0']);
        $device3 = Device::factory()->create(['firmware_version' => 'v1.5.0']);

        $latestVersion = Device::orderBy('firmware_version', 'desc')->first()->firmware_version;
        expect($latestVersion)->toBe('v2.0.0');
    });
}); 