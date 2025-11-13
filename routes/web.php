<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\PrestasiController;

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('siswa', SiswaController::class);
Route::resource('pelanggaran', PelanggaranController::class);
Route::post('pelanggaran/{pelanggaran}/verify', [PelanggaranController::class, 'verify'])->name('pelanggaran.verify');
Route::resource('prestasi', PrestasiController::class);
Route::post('prestasi/{prestasi}/verify', [PrestasiController::class, 'verify'])->name('prestasi.verify');
Route::post('prestasi/{prestasi}/reject', [PrestasiController::class, 'reject'])->name('prestasi.reject');

Route::get('laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
Route::get('laporan/siswa/pdf', [\App\Http\Controllers\LaporanController::class, 'siswaPdf'])->name('laporan.siswa.pdf');
Route::get('laporan/pelanggaran/pdf', [\App\Http\Controllers\LaporanController::class, 'pelanggaranPdf'])->name('laporan.pelanggaran.pdf');
Route::get('laporan/prestasi/pdf', [\App\Http\Controllers\LaporanController::class, 'prestasiPdf'])->name('laporan.prestasi.pdf');

Route::resource('bk', \App\Http\Controllers\BimbinganKonselingController::class);

