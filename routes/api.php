<?php

use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\FarmController;
use App\Http\Controllers\Api\V1\ShedController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->name('verification.verify');
    Route::post('/email/verify/resend', [AuthController::class, 'resendVerificationEmail']);
});

// Other routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResources([
        'users' => UserController::class,
        'farms' => FarmController::class,
        'sheds' => ShedController::class,
        'devices' => DeviceController::class,
    ]);
});
