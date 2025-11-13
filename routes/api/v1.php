<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'Hello from API v1']);
});

// Test route
Route::get('/test', fn() => response()->json(['version' => 'v1']));

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Public OTP routes with throttle
    Route::middleware(['throttle:5,1'])->group(function () {
        Route::post('forgot-password', [ForgotPasswordController::class, 'sendOtp']);
    });

    Route::post('verify-otp', [ForgotPasswordController::class, 'verifyOtp']);
    Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword']);

    // Protected routes
    Route::middleware(['auth:api'])->group(function () {
        Route::post('profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});
