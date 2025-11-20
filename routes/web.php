<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\UserController;

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
// Password reset (forgot password)
Route::get('forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin & Kesiswaan Routes
    Route::middleware('role:admin,kesiswaan')->group(function () {
        Route::resource('siswa', SiswaController::class);
        Route::resource('kelas', \App\Http\Controllers\KelasController::class);
        Route::resource('guru', \App\Http\Controllers\GuruController::class);
        Route::resource('jenis-pelanggaran', \App\Http\Controllers\JenisPelanggaranController::class);
        Route::resource('jenis-prestasi', \App\Http\Controllers\JenisPrestasiController::class);
        Route::resource('tahun-ajaran', \App\Http\Controllers\TahunAjaranController::class);
        Route::get('laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/siswa/pdf', [\App\Http\Controllers\LaporanController::class, 'siswaPdf'])->name('laporan.siswa.pdf');
        Route::get('laporan/pelanggaran/pdf', [\App\Http\Controllers\LaporanController::class, 'pelanggaranPdf'])->name('laporan.pelanggaran.pdf');
        Route::get('laporan/prestasi/pdf', [\App\Http\Controllers\LaporanController::class, 'prestasiPdf'])->name('laporan.prestasi.pdf');
        Route::get('sanksi', function() { return view('sanksi.index'); })->name('sanksi.index');
        
        // Verifikasi Routes
        Route::prefix('verifikasi')->name('verifikasi.')->group(function() {
            Route::get('/', [\App\Http\Controllers\VerifikasiController::class, 'index'])->name('dashboard');
            Route::get('/pelanggaran', [\App\Http\Controllers\VerifikasiController::class, 'pelanggaranMenunggu'])->name('pelanggaran');
            Route::get('/prestasi', [\App\Http\Controllers\VerifikasiController::class, 'prestasiMenunggu'])->name('prestasi');
            Route::get('/pelanggaran/{id}', [\App\Http\Controllers\VerifikasiController::class, 'pelanggaranDetail'])->name('pelanggaran.detail');
            Route::get('/prestasi/{id}', [\App\Http\Controllers\VerifikasiController::class, 'prestasiDetail'])->name('prestasi.detail');
            Route::post('/pelanggaran/{id}/approve', [\App\Http\Controllers\VerifikasiController::class, 'verifikasiPelanggaran'])->name('pelanggaran.approve');
            Route::post('/pelanggaran/{id}/reject', [\App\Http\Controllers\VerifikasiController::class, 'tolakPelanggaran'])->name('pelanggaran.reject');
            Route::post('/pelanggaran/{id}/revisi', [\App\Http\Controllers\VerifikasiController::class, 'revisiPelanggaran'])->name('pelanggaran.revisi');
            Route::post('/prestasi/{id}/approve', [\App\Http\Controllers\VerifikasiController::class, 'verifikasiPrestasi'])->name('prestasi.approve');
            Route::post('/prestasi/{id}/reject', [\App\Http\Controllers\VerifikasiController::class, 'tolakPrestasi'])->name('prestasi.reject');
            Route::post('/prestasi/{id}/revisi', [\App\Http\Controllers\VerifikasiController::class, 'revisiPrestasi'])->name('prestasi.revisi');
            Route::post('/pelanggaran/bulk-approve', [\App\Http\Controllers\VerifikasiController::class, 'bulkVerifikasiPelanggaran'])->name('pelanggaran.bulk-approve');
            Route::post('/prestasi/bulk-approve', [\App\Http\Controllers\VerifikasiController::class, 'bulkVerifikasiPrestasi'])->name('prestasi.bulk-approve');
            Route::get('/riwayat', [\App\Http\Controllers\VerifikasiController::class, 'riwayat'])->name('riwayat');
        });
    });
    
    // Admin Only Routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('backup', function() { return view('backup.index'); })->name('backup.index');
    });
    
    // Guru, Wali Kelas, Admin, Kesiswaan Routes
    Route::middleware('role:admin,kesiswaan,guru,wali_kelas')->group(function () {
        Route::resource('pelanggaran', PelanggaranController::class);
        Route::post('pelanggaran/{pelanggaran}/verify', [PelanggaranController::class, 'verify'])->name('pelanggaran.verify')->middleware('role:admin,kesiswaan');
        Route::post('pelanggaran/{pelanggaran}/reject', [PelanggaranController::class, 'reject'])->name('pelanggaran.reject')->middleware('role:admin,kesiswaan');
        Route::resource('prestasi', PrestasiController::class);
        Route::post('prestasi/{prestasi}/verify', [PrestasiController::class, 'verify'])->name('prestasi.verify')->middleware('role:admin,kesiswaan');
        Route::post('prestasi/{prestasi}/reject', [PrestasiController::class, 'reject'])->name('prestasi.reject')->middleware('role:admin,kesiswaan');
        Route::resource('bk', \App\Http\Controllers\BimbinganKonselingController::class);
    });
});