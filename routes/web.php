<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Web\ChartController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function() {
    return view('auth.login');
});
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/forget-password', function() {
    return view('auth.forget');
});
Route::post('/forget-password', [AuthController::class, 'forgotPassword'])->name('forget');

Route::group(['prefix' => 'admin','middleware' => ['auth', 'role:admin']], function() {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/import-chart', [ChartController::class, 'import'])->name('import.chart');

    Route::resources([
        'clients' => ClientController::class,
        'charts' => ChartController::class,
    ]);
});


