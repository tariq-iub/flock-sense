<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\BreedController;
use App\Http\Controllers\Web\ChartController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\FarmController;
use App\Http\Controllers\Web\FeedController;
use App\Http\Controllers\Web\ProductionLogController;
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

    // Import chart route (POST)
    Route::post('/import-chart', [ChartController::class, 'import'])->name('import.chart');
    Route::get('/charts/data/{chart}', [ChartController::class, 'chartData'])->name('charts.data');

    Route::get('/roles/{role}/permissions', [RoleController::class, 'getPermissions'])->name('roles.permissions');
    Route::post('/roles/permissions', [RoleController::class, 'setPermissions'])->name('roles.set-permissions');
    Route::get('/roles/{role}/users', [RoleController::class, 'attachedUsers'])->name('roles.users');

    Route::get('/farms/{farm}/data', [FarmController::class, 'farmData'])->name('farms.data');

    // Production Log Routes
    Route::get('log/productions/export/excel', [ProductionLogController::class, 'exportExcel'])->name('productions.export.excel');
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
        'clients' => ClientController::class,
        'roles' => RoleController::class,
        'charts' => ChartController::class,
        'breeding' => BreedController::class,
        'feeds' => FeedController::class,
        'farms' => FarmController::class,
        'log/productions' => ProductionLogController::class,
    ]);
});
