<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/disruptions', [\App\Http\Controllers\Api\DisruptionController::class, 'index']);

// Protected SaaS Customer Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Manage your supply chain
    Route::get('/supply-locations', [\App\Http\Controllers\Api\Customer\SupplyLocationController::class, 'index']);
    Route::post('/supply-locations', [\App\Http\Controllers\Api\Customer\SupplyLocationController::class, 'store']);
    Route::get('/supply-locations/{id}', [\App\Http\Controllers\Api\Customer\SupplyLocationController::class, 'show']);
    Route::put('/supply-locations/{id}', [\App\Http\Controllers\Api\Customer\SupplyLocationController::class, 'update']);
    Route::delete('/supply-locations/{id}', [\App\Http\Controllers\Api\Customer\SupplyLocationController::class, 'destroy']);
    
    // View your alerts
    Route::get('/alerts', [\App\Http\Controllers\Api\AlertController::class, 'index']);
});
