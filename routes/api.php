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
        
        Route::apiResource('siswa', SiswaApiController::class)->names([
            'index' => 'api.siswa.index',
            'store' => 'api.siswa.store',
            'show' => 'api.siswa.show',
            'update' => 'api.siswa.update',
            'destroy' => 'api.siswa.destroy',
        ]);
        Route::apiResource('pelanggaran', PelanggaranApiController::class)->names([
            'index' => 'api.pelanggaran.index',
            'store' => 'api.pelanggaran.store',
            'show' => 'api.pelanggaran.show',
            'update' => 'api.pelanggaran.update',
            'destroy' => 'api.pelanggaran.destroy',
        ]);
        Route::apiResource('prestasi', PrestasiApiController::class)->names([
            'index' => 'api.prestasi.index',
            'store' => 'api.prestasi.store',
            'show' => 'api.prestasi.show',
            'update' => 'api.prestasi.update',
            'destroy' => 'api.prestasi.destroy',
        ]);
    });
});
