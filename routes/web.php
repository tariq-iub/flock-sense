<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function() {
    return view('auth.login');
});

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['prefix' => 'admin','middleware' => ['auth', 'role:admin']], function() {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resources([
        'users' => UserController::class,
    ]);
});


