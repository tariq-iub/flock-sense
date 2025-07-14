<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ChartController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\DashboardController;
use Illuminate\Support\Facades\Route;

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

    // Resource routes for clients and charts
    Route::resources([
        'clients' => ClientController::class,
        'charts' => ChartController::class,
    ]);
});
