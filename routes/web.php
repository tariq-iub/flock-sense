<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\BreedController;
use App\Http\Controllers\Web\ChartController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\IotController;
use App\Http\Controllers\Web\FarmController;
use App\Http\Controllers\Web\ExpenseController;
use App\Http\Controllers\Web\FeedController;
use App\Http\Controllers\Web\MedicineController;
use App\Http\Controllers\Web\PricingController;
use App\Http\Controllers\Web\ProductionLogController;
use App\Http\Controllers\Web\ReportsController;
use App\Http\Controllers\Web\RoleController;
use Illuminate\Support\Facades\Route;
use App\Models\Flock;
use App\Models\Shed;

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Login and forget-password routes (outside admin group)
Route::get('/login', function () {
    return view('auth.login');
});
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/forget-password', function () {
    return view('auth.forget');
});
Route::post('/forget-password', [AuthController::class, 'forgotPassword'])->name('forget');

// Password reset routes
Route::get('/reset-password/{token}', function ($token) {
    $email = request('email');
    return view('auth.reset', compact('token', 'email'));
})->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Email verification routes
Route::get('/email/verify', function () {
    return view('auth.verification');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Admin routes group with auth and role:admin middleware
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () {
    // Register routes (GET and POST)
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    // Logout route (POST)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard routes (both GET and POST)
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/farms/{farm}/data', [FarmController::class, 'farmData'])->name('farms.data');
    Route::get('/farm-devices', [IotController::class, 'farmDevices'])->name('farm.devices');
    Route::post('/farm-devices/link', [IotController::class, 'link'])->name('farm.devices.link');
    Route::post('/farm-devices/delink', [IotController::class, 'delink'])->name('farm.devices.delink');

    Route::get('/get-sheds', function (\Illuminate\Http\Request $request) {
        return Shed::where('farm_id', $request->farm_id)
            ->select('id', 'name', 'capacity', 'type')
            ->orderBy('name')
            ->get();
    });
    Route::get('/get-flocks', function (\Illuminate\Http\Request $request) {
        return Flock::where('shed_id', $request->shed_id)
            ->select('id', 'name', 'start_date', 'end_date')
            ->orderBy('start_date', 'desc')
            ->get();
    });

    // Resource routes for clients and charts
    Route::resources([
        'breeding' => BreedController::class,
        'feeds' => FeedController::class,
        'medicines' => MedicineController::class,
        'farms' => FarmController::class,
        'pricings' => PricingController::class,
    ]);

    // Users and Clients
    Route::prefix('clients')->controller(ClientController::class)->group(function () {
        Route::get('/', 'index')->name('clients.index');
        Route::post('/', 'store')->name('clients.store');
        Route::get('/{user}', 'show')->name('clients.show');
        Route::get('/{user}/edit', 'edit')->name('clients.edit');
        Route::put('/{user}', 'update')->name('clients.update');
        Route::delete('/{user}', 'destroy')->name('clients.destroy');
//        Route::get('/{chart}/toggle', 'toggle')->name('clients.toggle');
    });

    // Charts (Standard) Data Routes
    Route::prefix('charts')->controller(ChartController::class)->group(function () {
        Route::get('/', 'index')->name('charts.index');
        Route::get('/{chart}/edit', 'edit')->name('charts.edit');
        Route::put('/{chart}', 'update')->name('charts.update');
        Route::delete('/{chart}', 'destroy')->name('charts.destroy');
        Route::post('/import', 'import')->name('charts.import');
        Route::get('/data/{chart}', 'chartData')->name('charts.data');
        Route::get('/{chart}/toggle', 'toggle')->name('charts.toggle');
        Route::post('/data/update', 'data_update')->name('charts.data.update');

    });

    // Roles
    Route::prefix('roles')->controller(RoleController::class)->group(function () {
        Route::get('/', 'index')->name('roles.index');
        Route::post('/', 'store')->name('roles.store');
        Route::put('/{role}', 'update')->name('roles.update');
        Route::delete('/{role}', 'destroy')->name('roles.destroy');
        Route::get('/{role}/permissions', [RoleController::class, 'getPermissions'])->name('roles.permissions');
        Route::post('/permissions', [RoleController::class, 'setPermissions'])->name('roles.set-permissions');
        Route::get('/{role}/users', [RoleController::class, 'attachedUsers'])->name('roles.users');
    });

    // IoT
    Route::prefix('iot')->controller(IotController::class)->group(function () {
        Route::get('/', 'index')->name('iot.index');
        Route::get('/create', 'create')->name('iot.create');
        Route::post('/', 'store')->name('iot.store');
        Route::get('/{device}', 'show')->name('iot.show');
        Route::get('/{device}/edit', 'edit')->name('iot.edit');
        Route::put('/{device}', 'update')->name('iot.update');
        Route::delete('/{device}', 'destroy')->name('iot.destroy');
        Route::get('/alerts', 'alerts')->name('iot.alerts');
        Route::get('/logs', 'logs')->name('iot.logs');
    });

    // Expenses
    Route::prefix('expenses')->controller(ExpenseController::class)->group(function () {
        Route::get('/', 'index')->name('expenses.index');
        Route::post('/', 'store')->name('expenses.store');
        Route::put('/expenses/{expense}', 'update')->name('expenses.update');
        Route::delete('/expenses/{expense}', 'delete')->name('expenses.destroy');
        Route::get('/expenses/{expense}/toggle', 'toggle')->name('expenses.toggle');
    });

    // Productions Logs
    Route::prefix('productions')->controller(ProductionLogController::class)->group(function () {
        Route::get('/', 'index')->name('productions.index');
        Route::get('/create', 'create')->name('productions.create');
        Route::post('/', 'store')->name('productions.store');
        Route::get('/{productionLog}', 'show')->name('productions.show');
        Route::get('/{productionLog}/edit', 'edit')->name('productions.edit');
        Route::put('/{productionLog}', 'update')->name('productions.update');
        Route::delete('/{productionLog}', 'destroy')->name('productions.destroy');
        Route::get('/export/excel', 'exportExcel')->name('productions.export.excel');
    });

    // Reports
    Route::prefix('reports')->controller(ReportsController::class)->group(function () {
        Route::get('/income', 'income')->name('reports.income');
        Route::get('/expenses', 'expenses')->name('reports.expenses');
        Route::get('/tax', 'tax')->name('reports.tax');
        Route::get('/devices-sold', 'devices_sales')->name('reports.devices.sales');
        Route::get('/annual', 'annual')->name('reports.annual');
    });

});
