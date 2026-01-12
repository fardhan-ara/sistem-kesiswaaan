<?php

// Contoh penggunaan routes untuk sistem wali kelas baru

// Admin routes untuk assign wali kelas
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('homeroom-teachers', HomeroomTeacherController::class)->except(['show', 'edit', 'update']);
    
    // API endpoints untuk assign wali kelas
    Route::post('api/homeroom-teachers', [HomeroomTeacherController::class, 'apiStore'])->name('api.homeroom-teachers.store');
    Route::delete('api/homeroom-teachers/{homeroomTeacher}', [HomeroomTeacherController::class, 'apiDestroy'])->name('api.homeroom-teachers.destroy');
});

// Wali kelas routes dengan middleware baru
Route::middleware(['auth', 'homeroom_teacher'])->prefix('wali-kelas')->name('wali-kelas.')->group(function () {
    Route::get('/dashboard', [WaliKelasController::class, 'dashboard'])->name('dashboard');
    Route::get('/siswa', [WaliKelasController::class, 'siswa'])->name('siswa');
    Route::get('/siswa/{id}', [WaliKelasController::class, 'siswaShow'])->name('siswa.show');
    
    // Routes dengan parameter kelas tertentu
    Route::middleware('homeroom_teacher:{kelas_id}')->group(function () {
        Route::get('/kelas/{kelas_id}/absen', [AbsenController::class, 'index'])->name('absen.index');
        Route::post('/kelas/{kelas_id}/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    });
});

// Contoh penggunaan middleware dengan parameter kelas
Route::get('/kelas/{kelas}/detail', function ($kelasId) {
    // Hanya wali kelas dari kelas ini yang bisa akses
    return "Detail kelas {$kelasId}";
})->middleware(['auth', 'homeroom_teacher:' . request()->route('kelas')]);

?>