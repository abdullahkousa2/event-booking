<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\EventApiController;
use Illuminate\Support\Facades\Route;

// Public API endpoints
Route::prefix('get')->group(function () {
    Route::get('/events', [EventApiController::class, 'index']);
});

// Protected API endpoints (require Sanctum Bearer token)
Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('post')->group(function () {
    Route::post('/bookings', [BookingApiController::class, 'store']);
});

// Token management
Route::post('/auth/token', [ApiAuthController::class, 'issue']);
Route::delete('/auth/token', [ApiAuthController::class, 'revoke'])->middleware('auth:sanctum');
