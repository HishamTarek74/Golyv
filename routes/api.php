<?php

use App\Http\Controllers\API\AuthenticationController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\TripController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Authentication System
Route::prefix('auth')->as('auth.')->group(function () {
    Route::post('login', [AuthenticationController::class, 'login'])->name('login');
    Route:: as('user.')->middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');
    });
});
Route::get('/trips/available-seats', [TripController::class, 'getAvailableSeats']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bookings', [BookingController::class, 'bookSeat']);
});
