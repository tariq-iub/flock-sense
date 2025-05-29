<?php

use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\FarmController;
use App\Http\Controllers\Api\V1\ShedController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResources([
        'users' => UserController::class,
        'farms' => FarmController::class,
        'sheds' => ShedController::class,
        'devices' => DeviceController::class,
    ]);
});
