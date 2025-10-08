<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\Api\AuthController; // ← import para auth

// Lo que ya tenías
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/health', [HealthController::class, 'show']);

// *** NUEVO: Rutas de autenticación (Sanctum) ***
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me',      [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware(['auth:sanctum','throttle:60,1'])->group(function () {
    Route::get('/tags',  [TagController::class, 'index']);
    Route::post('/tags', [TagController::class, 'store']);

    Route::get('/tasks',           [TaskController::class, 'index']);
    Route::post('/tasks',          [TaskController::class, 'store']);
    Route::get('/tasks/{task}',    [TaskController::class, 'show']);
    Route::put('/tasks/{task}',    [TaskController::class, 'update']);
    Route::patch('/tasks/{task}',  [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    Route::post('/tasks/{task}/toggle', [TaskController::class, 'toggle']);
});
