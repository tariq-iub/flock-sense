<?php

use App\Http\Controllers\Api\V1\BreedController;
use App\Http\Controllers\Api\V1\DeviceApplianceController;
use App\Http\Controllers\Api\V1\FlockController;
use App\Http\Controllers\Api\V1\SensorDataController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\SubscriptionPlanController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\FarmController;
use App\Http\Controllers\Api\V1\ShedController;
use App\Http\Controllers\Api\V1\DeviceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Other routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class)->except(['store']);

    Route::apiResources([
        'farms' => FarmController::class,
        'sheds' => ShedController::class,
        'devices' => DeviceController::class,
        'plans' => SubscriptionPlanController::class,
        'subscriptions' => SubscriptionController::class,
        'breeds' => BreedController::class,
        'flocks' => FlockController::class,
    ]);

    Route::prefix('sensor-data')->controller(SensorDataController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/shed/{shedId}', 'fetchByShed');
        Route::get('/farm/{farmId}', 'fetchByFarm');
    });
});

// Resource routes
Route::apiResource('device-appliances', DeviceApplianceController::class);

// Custom routes
Route::get('shed/{shedId}/appliances', [DeviceApplianceController::class, 'fetchByShed']);
Route::get('device/{serial}/appliances', [DeviceApplianceController::class, 'fetchByDevice']);
Route::get('device/{serial}/appliance-ids', [DeviceApplianceController::class, 'fetchDeviceApplianceIds']);

// Status routes
Route::put('device-appliances/{deviceAppliance}/status', [DeviceApplianceController::class, 'updateStatus']);
Route::put('device-appliances/statuses/update', [DeviceApplianceController::class, 'updateAllStatuses']);
Route::get('device-appliances/statuses', [DeviceApplianceController::class, 'getAllStatuses']);
Route::get('device-appliances/{deviceAppliance}/status', [DeviceApplianceController::class, 'getStatus']);
