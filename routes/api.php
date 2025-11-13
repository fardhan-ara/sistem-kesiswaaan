<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SiswaApiController;
use App\Http\Controllers\Api\PelanggaranApiController;
use App\Http\Controllers\Api\PrestasiApiController;

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        Route::get('dashboard', [\App\Http\Controllers\Api\DashboardApiController::class, 'index']);
        
        Route::apiResource('siswa', SiswaApiController::class);
        Route::apiResource('pelanggaran', PelanggaranApiController::class);
        Route::apiResource('prestasi', PrestasiApiController::class);
    });
});
