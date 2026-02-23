<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Controllers\RegisterCompanyController;
use App\Modules\Auth\Controllers\LoginController;
use App\Modules\Auth\Controllers\RefreshTokenController;
use App\Modules\Auth\Controllers\DeviceController;

// ─── Public routes ────────────────────────────────────────────
Route::prefix('v1/auth')->group(function () {
    Route::post('/register-company', RegisterCompanyController::class)
        ->middleware('throttle:20,1'); // max 20x per menit per IP

    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:10,1'); // brute-force protection

    Route::post('/refresh-token', RefreshTokenController::class)
        ->middleware('throttle:60,1'); // cukup longgar, tapi tetap dibatasi
});

// ─── Protected routes (JWT required) ─────────────────────────
Route::prefix('v1/auth')->middleware('jwt.auth')->group(function () {
    Route::get('/devices', [DeviceController::class, 'index']);                         // semua device aktif
    Route::get('/devices/current', [DeviceController::class, 'current']);               // device yang sedang login
    Route::delete('/devices/{sessionId}', [DeviceController::class, 'logoutDevice']);   // logout device lain
    Route::post('/logout', [DeviceController::class, 'logout']);        // logout device ini (auto dari JWT)
    Route::post('/logout-all', [DeviceController::class, 'logoutAll']); // logout semua device
});
