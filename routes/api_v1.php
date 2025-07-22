<?php

use App\Http\Controllers\Api\V1\BreedController;
use App\Http\Controllers\Api\V1\DeviceApplianceController;
use App\Http\Controllers\Api\V1\FlockController;
use App\Http\Controllers\Api\V1\MedicineController;
use App\Http\Controllers\Api\V1\ProductionLogController;
use App\Http\Controllers\Api\V1\SensorDataController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\SubscriptionPlanController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\FarmController;
use App\Http\Controllers\Api\V1\ShedController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\FarmManagerController;
use App\Http\Controllers\Api\V1\FarmStaffController;
use App\Http\Controllers\Api\V1\UserSettingsController;
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
        'settings' => UserSettingsController::class,
        'production' => ProductionLogController::class,
        'medicines' => MedicineController::class,
    ]);

    // Farm managers
    Route::get('farms/{farm}/managers', [FarmManagerController::class, 'index']);
    Route::post('farms/{farm}/managers', [FarmManagerController::class, 'store']);
    Route::get('farms/{farm}/managers/{user}', [FarmManagerController::class, 'show']);
    Route::put('farms/{farm}/managers/{user}', [FarmManagerController::class, 'update']);
    Route::delete('farms/{farm}/managers/{user}', [FarmManagerController::class, 'destroy']);

    // Farm staff
    Route::get('farms/{farm}/staff', [FarmStaffController::class, 'index']);
    Route::post('farms/{farm}/staff', [FarmStaffController::class, 'store']);
    Route::get('farms/{farm}/staff/{user}', [FarmStaffController::class, 'show']);
    Route::put('farms/{farm}/staff/{user}', [FarmStaffController::class, 'update']);
    Route::delete('farms/{farm}/staff/{user}', [FarmStaffController::class, 'destroy']);

    Route::prefix('sensor-data')->controller(SensorDataController::class)->group(function () {
        Route::get('/shed/{shedId}', 'fetchByShed');
        Route::get('/farm/{farmId}', 'fetchByFarm');
    });

    Route::put('/settings/{user}', [UserSettingsController::class, 'update']);
});

// Public sensor data store route (no auth required)
Route::post('sensor-data', [SensorDataController::class, 'store']);

// Resource routes
Route::apiResource('device-appliances', DeviceApplianceController::class);

// Custom routes
Route::get('shed/{shedId}/appliances', [DeviceApplianceController::class, 'fetchByShed']);
Route::get('device/{serial}/appliances', [DeviceApplianceController::class, 'fetchByDevice']);
Route::get('device/{serial}/appliance-ids', [DeviceApplianceController::class, 'fetchDeviceApplianceIds']);

// Status routes - Updated for key-based approach
Route::post('device-appliances/update-status', [DeviceApplianceController::class, 'updateStatus']);
Route::post('device-appliances/update-all-statuses', [DeviceApplianceController::class, 'updateAllStatuses']);

// Legacy routes
Route::get('device-appliances/statuses', [DeviceApplianceController::class, 'getAllStatuses']);
Route::get('device-appliances/{deviceAppliance}/status', [DeviceApplianceController::class, 'getStatus']);
