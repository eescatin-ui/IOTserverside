<?php

use App\Http\Controllers\API\SensorController;
use App\Http\Controllers\API\ActuatorController;
use App\Http\Controllers\API\AuthController;

// Test route
Route::get('/test', function() {
    return response()->json(['message' => 'API is working!']);
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ESP32 routes - PUBLIC (no auth)
Route::post('/sensors/data', [SensorController::class, 'ingest']);
Route::get('/actuators/status', [ActuatorController::class, 'getStatus']);
Route::get('/esp32/duration', [ActuatorController::class, 'getDurationForESP32']);

// Mobile app routes - Controllers handle auth manually via api_token
Route::get('/mobile/dashboard', [App\Http\Controllers\API\MobileController::class, 'dashboard']);
Route::get('/sensors/latest', [SensorController::class, 'latest']);
Route::get('/profile', [AuthController::class, 'profile']);
Route::get('/users', [AuthController::class, 'listUsers']);
Route::get('/activities', [AuthController::class, 'activities']);
Route::post('/actuators/control', [ActuatorController::class, 'control']);
Route::get('/actuators/duration', [ActuatorController::class, 'getDuration']);
Route::post('/actuators/duration', [ActuatorController::class, 'setDuration']);

// Web dashboard routes
Route::get('/dashboard', [App\Http\Controllers\API\DashboardController::class, 'dashboard']);
Route::get('/dashboard/poll', [App\Http\Controllers\API\DashboardController::class, 'dashboardPoll']);
Route::get('/connection/status', [App\Http\Controllers\API\DashboardController::class, 'connectionStatus']);
Route::post('/actuators/web-control', [App\Http\Controllers\API\DashboardController::class, 'webControl']);