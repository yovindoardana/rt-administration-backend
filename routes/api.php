<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\ResidentHouseHistoryController;

// Auth routes
Route::post('/register',        [AuthController::class, 'register']);
Route::post('/login',           [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password',  [AuthController::class, 'resetPassword']);


// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // House routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);

        Route::apiResource('houses', HouseController::class);
        Route::apiResource('residents', ResidentController::class);
        Route::apiResource('resident-house-histories', ResidentHouseHistoryController::class);
        Route::apiResource('payments', PaymentController::class);
        Route::apiResource('expenses', ExpenseController::class);
    });
});
