<?php

use App\Http\Controllers\Api\V1\BreedController;
use App\Http\Controllers\Api\V1\DeviceApplianceController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\FarmController;
use App\Http\Controllers\Api\V1\FarmManagerController;
use App\Http\Controllers\Api\V1\FarmStaffController;
use App\Http\Controllers\Api\V1\FlockController;
use App\Http\Controllers\Api\V1\MedicineController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\ProductionLogController;
use App\Http\Controllers\Api\V1\SensorDataController;
use App\Http\Controllers\Api\V1\ShedController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\SubscriptionPlanController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UserSettingsController;
use App\Http\Controllers\Api\V1\IoTDeviceDataController;
use App\Models\District;
use App\Models\Province;
use App\Models\Tehsil;
use App\Http\Controllers\Api\V1\WeightLogController;
use App\Http\Controllers\Api\V1\GraphDataController;
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

    Route::post('farms/create', [FarmController::class, 'createFarmWithShedAndFlock']);

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

    Route::get('notifications', [NotificationController::class, 'index']);
    Route::patch('notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::patch('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
});

// Public sensor data store route (no auth required)
Route::post('sensor-data', [SensorDataController::class, 'store']);

// Push Sensor Data and Device Appliance Data
Route::post('device/sync-data', [SensorDataController::class, 'syncDeviceData']);

// Public sensor data store routes (with timestamp + multiple records)
Route::post('sensor-data/with-timestamp', [SensorDataController::class, 'storeWithTimestamp']);
Route::post('sensor-data/multiple', [SensorDataController::class, 'storeMultiple']);

// Device data sync with timestamp
Route::post('device/sync-data-with-timestamp', [SensorDataController::class, 'syncDeviceDataWithTimestamp']);

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

// Daily reports
Route::get('production/report/headers/{id}', [ProductionLogController::class, 'dailyReportHeaders'])->name('productions.report.headers');
Route::get('production/report/dates/{id}', [ProductionLogController::class, 'productionDatesByFlock'])->name('productions.report.dates');
Route::get('production/report/history', [ProductionLogController::class, 'history'])->name('productions.report.history');
Route::get('production/report/latest', [ProductionLogController::class, 'latestHistory'])->name('productions.report.latest');
Route::get('daily-report/{version}', [ProductionLogController::class, 'dailyReport'])->name('productions.daily.report');

Route::get('weight-log/history', [WeightLogController::class, 'history'])->name('weight-log.history');

Route::get('/mortality-rate', [GraphDataController::class, 'mortalityRate']);
Route::get('/adg-weight', [GraphDataController::class, 'adgAndWeight']);
Route::get('/feed-weight-cumulative', [GraphDataController::class, 'feedWeightCumulative']);
Route::get('/fcr', [GraphDataController::class, 'fcr']);
Route::get('/water-feed', [GraphDataController::class, 'waterToFeedRatio']);
Route::get('/uniformity', [GraphDataController::class, 'uniformity']);
Route::get('/vaccination-history', [GraphDataController::class, 'vaccinationHistory']);
Route::get('/feed-consumption-history', [GraphDataController::class, 'feedConsumptionHistory']);
Route::get('/water-consumption-history', [GraphDataController::class, 'waterConsumptionHistory']);

Route::prefix('iot')->group(function () {
    // 1. Store sensor data (single record)
    Route::post('/sensor', [IoTDeviceDataController::class, 'storeSensor']);

    // 2. Store multiple sensor data records
    Route::post('/sensors', [IoTDeviceDataController::class, 'storeMultipleSensor']);

    // 3. Update appliance data (single snapshot)
    Route::post('/appliances', [IoTDeviceDataController::class, 'updateAppliance']);

    // 4. Update multiple appliance records
    Route::post('/appliances/multiple', [IoTDeviceDataController::class, 'updateMultipleAppliances']);

    // 5. Sync device data (appliances + sensors, single record)
    Route::post('/sync', [IoTDeviceDataController::class, 'syncDeviceData']);

    // 6. Sync multiple device data records
    Route::post('/sync/multiple', [IoTDeviceDataController::class, 'syncMultipleDeviceData']);
});

// Address Credentials
Route::get('provinces', function () {
    return Province::select('id', 'name')
        ->orderBy('name')
        ->get();
})->name('provinces');

Route::get('districts/{provinceId}', function ($provinceId) {
    return District::select('id', 'name')
        ->where('province_id', $provinceId)
        ->orderBy('name')
        ->get();
})->name('districts');

Route::get('cities/{districtId}', function ($districtId) {
    return Tehsil::select('id', 'name')
        ->where('district_id', $districtId)
        ->orderBy('name')
        ->get();
})->name('cities');

Route::fallback(function () {
    return response()->json([
        'status' => 0,
        'message' => 'API endpoint not found.',
    ], 404);
});
