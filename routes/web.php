<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SanksiController;

// Welcome page - Landing Page
Route::get('/', [AuthController::class, 'showLanding'])->name('landing');
Route::get('/welcome', [AuthController::class, 'showLanding'])->name('welcome');

// Auth Routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::get('admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');
// Password reset (forgot password)
Route::get('forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');
// Public signup for non-admin roles
Route::get('signup', [AuthController::class, 'showPublicRegister'])->name('signup');
Route::post('signup', [AuthController::class, 'publicRegister'])->name('signup.post');
Route::post('remove-user-data', [AuthController::class, 'removeUserData'])->name('remove.user.data');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Email verification routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware('signed')->name('verification.verify');
    Route::post('/email/resend', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')->name('verification.send');
});

// Settings route with verified middleware example
Route::get('/settings', function () {
    return view('settings.index');
})->middleware(['auth', 'verified'])->name('settings');

// Admin & Kesiswaan Routes
Route::middleware(['auth'])->group(function () {
    // Siswa routes - hanya admin & kesiswaan
    Route::middleware('role:admin,kesiswaan')->group(function () {
        Route::resource('siswa', SiswaController::class);
        Route::post('siswa/{id}/approve', [SiswaController::class, 'approve'])->name('siswa.approve');
        Route::post('siswa/{id}/reject', [SiswaController::class, 'reject'])->name('siswa.reject');
    });
    
    // Kelas routes - hanya admin & kesiswaan
    Route::middleware('role:admin,kesiswaan')->group(function () {
        Route::resource('kelas', \App\Http\Controllers\KelasController::class)->parameters([
            'kelas' => 'kelas'
        ]);
    });
    
    // Guru routes - hanya admin & kesiswaan
    Route::middleware('role:admin,kesiswaan')->group(function () {
        Route::resource('guru', \App\Http\Controllers\GuruController::class);
        Route::post('guru/{id}/approve', [\App\Http\Controllers\GuruController::class, 'approve'])->name('guru.approve');
        Route::post('guru/{id}/reject', [\App\Http\Controllers\GuruController::class, 'reject'])->name('guru.reject');
    });
    
    // Jenis Pelanggaran & Prestasi - hanya admin & kesiswaan
    Route::middleware('role:admin,kesiswaan')->group(function () {
        Route::resource('jenis-pelanggaran', \App\Http\Controllers\JenisPelanggaranController::class);
        Route::get('api/jenis-pelanggaran/{id}/usage', [\App\Http\Controllers\JenisPelanggaranController::class, 'usage']);
        Route::resource('jenis-prestasi', \App\Http\Controllers\JenisPrestasiController::class);
        Route::resource('tahun-ajaran', \App\Http\Controllers\TahunAjaranController::class);
        Route::post('tahun-ajaran/{id}/approve', [\App\Http\Controllers\TahunAjaranController::class, 'approve'])->name('tahun-ajaran.approve');
        Route::post('tahun-ajaran/{id}/reject', [\App\Http\Controllers\TahunAjaranController::class, 'reject'])->name('tahun-ajaran.reject');
    });
    
    // Laporan - hanya admin & kesiswaan
    Route::middleware('role:admin,kesiswaan')->group(function () {
        Route::get('laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/siswa/pdf', [\App\Http\Controllers\LaporanController::class, 'siswaPdf'])->name('laporan.siswa.pdf');
        Route::get('laporan/pelanggaran/pdf', [\App\Http\Controllers\LaporanController::class, 'pelanggaranPdf'])->name('laporan.pelanggaran.pdf');
        Route::get('laporan/prestasi/pdf', [\App\Http\Controllers\LaporanController::class, 'prestasiPdf'])->name('laporan.prestasi.pdf');
    });
    
    // Sanksi - hanya admin & kesiswaan
    Route::middleware('role:admin,kesiswaan')->group(function () {
        Route::resource('sanksi', \App\Http\Controllers\SanksiController::class);
    });
});

// Protected Routes (require authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Verifikasi Routes (Admin, Kesiswaan, Kepala Sekolah)
    Route::middleware('role:admin,kesiswaan,kepala_sekolah')->group(function () {
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
    
    // Kepala Sekolah Routes
    Route::middleware('role:kepala_sekolah')->prefix('kepala-sekolah')->name('kepala-sekolah.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\KepalaSekolahController::class, 'dashboard'])->name('dashboard');
        Route::get('/monitoring-pelanggaran', [\App\Http\Controllers\KepalaSekolahController::class, 'monitoringPelanggaran'])->name('monitoring-pelanggaran');
        Route::get('/monitoring-sanksi', [\App\Http\Controllers\KepalaSekolahController::class, 'monitoringSanksi'])->name('monitoring-sanksi');
        Route::get('/monitoring-prestasi', [\App\Http\Controllers\KepalaSekolahController::class, 'monitoringPrestasi'])->name('monitoring-prestasi');
        Route::get('/laporan-executive', [\App\Http\Controllers\KepalaSekolahController::class, 'laporanExecutive'])->name('laporan-executive');
        Route::get('/laporan-pdf', [\App\Http\Controllers\KepalaSekolahController::class, 'exportLaporanPDF'])->name('laporan-pdf');
        Route::post('/rekomendasi', [\App\Http\Controllers\KepalaSekolahController::class, 'storeRekomendasi'])->name('rekomendasi.store');
        Route::delete('/rekomendasi/{id}', [\App\Http\Controllers\KepalaSekolahController::class, 'deleteRekomendasi'])->name('rekomendasi.delete');
    });
    
    // Admin Only Routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::post('users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
        
        // Role Management (Merged)
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('role-management', [\App\Http\Controllers\RoleManagementController::class, 'index'])->name('role-management');
            Route::post('assign-dual-role', [\App\Http\Controllers\RoleManagementController::class, 'assignDualRole'])->name('assign-dual-role');
            Route::get('edit-dual-role/{id}', [\App\Http\Controllers\RoleManagementController::class, 'editDualRole'])->name('edit-dual-role');
            Route::put('update-dual-role/{id}', [\App\Http\Controllers\RoleManagementController::class, 'updateDualRole'])->name('update-dual-role');
            Route::delete('remove-dual-role/{id}', [\App\Http\Controllers\RoleManagementController::class, 'removeDualRole'])->name('remove-dual-role');
        });
        
        Route::get('backup', [\App\Http\Controllers\BackupController::class, 'index'])->name('backup.index');
        Route::post('backup/create', [\App\Http\Controllers\BackupController::class, 'create'])->name('backup.create');
        Route::get('backup/download/{fileName}', [\App\Http\Controllers\BackupController::class, 'download'])->name('backup.download');
        Route::delete('backup/{fileName}', [\App\Http\Controllers\BackupController::class, 'delete'])->name('backup.delete');
        Route::post('backup/restore', [\App\Http\Controllers\BackupController::class, 'restore'])->name('backup.restore');
        
        // Notifikasi - Admin kirim notifikasi
        Route::get('notifications/create', [\App\Http\Controllers\NotificationController::class, 'create'])->name('notifications.create');
        Route::post('notifications', [\App\Http\Controllers\NotificationController::class, 'store'])->name('notifications.store');
    });
    
    // Notifikasi - Semua user
    Route::get('notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('notifications/sent/{id}', [\App\Http\Controllers\NotificationController::class, 'destroySent'])->name('notifications.sent.destroy');
    
    // CSRF Refresh Route
    Route::get('refresh-csrf', function() {
        return response()->json(['token' => csrf_token()]);
    });
    
    // Profile Routes - Semua user kecuali admin
    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::get('profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    // Biodata Ortu Routes
    Route::post('biodata-ortu', [\App\Http\Controllers\BiodataOrtuController::class, 'store'])->name('biodata-ortu.store');
    
    // Komunikasi & Pembinaan Ortu Routes (Kesiswaan, Wali Kelas, BK, Ortu)
    Route::middleware('role:kesiswaan,wali_kelas,bk,ortu')->prefix('komunikasi')->name('komunikasi.')->group(function() {
        Route::get('/', [\App\Http\Controllers\KomunikasiOrtuController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\KomunikasiOrtuController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\KomunikasiOrtuController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\KomunikasiOrtuController::class, 'show'])->name('show');
        Route::post('/{id}/reply', [\App\Http\Controllers\KomunikasiOrtuController::class, 'reply'])->name('reply');
        
        Route::get('/panggilan/list', [\App\Http\Controllers\KomunikasiOrtuController::class, 'panggilan'])->name('panggilan');
        Route::get('/panggilan/create', [\App\Http\Controllers\KomunikasiOrtuController::class, 'createPanggilan'])->name('create-panggilan');
        Route::post('/panggilan/store', [\App\Http\Controllers\KomunikasiOrtuController::class, 'storePanggilan'])->name('store-panggilan');
        Route::post('/panggilan/{id}/konfirmasi', [\App\Http\Controllers\KomunikasiOrtuController::class, 'konfirmasiPanggilan'])->name('konfirmasi-panggilan');
        Route::post('/panggilan/{id}/selesaikan', [\App\Http\Controllers\KomunikasiOrtuController::class, 'selesaikanPanggilan'])->name('selesaikan-panggilan');
        Route::delete('/{id}', [\App\Http\Controllers\KomunikasiOrtuController::class, 'destroy'])->name('destroy');
    });
    
    // API Helper Routes
    Route::get('/api/get-ortu-by-siswa/{siswa_id}', function($siswa_id) {
        $biodata = \App\Models\BiodataOrtu::where('siswa_id', $siswa_id)->first();
        return response()->json(['ortu' => $biodata ? $biodata->user : null]);
    });
    
    Route::get('/api/get-pelanggaran-by-siswa/{siswa_id}', function($siswa_id) {
        $pelanggarans = \App\Models\Pelanggaran::where('siswa_id', $siswa_id)
            ->with('jenisPelanggaran')
            ->latest()->take(10)->get()
            ->map(function($p) {
                return [
                    'id' => $p->id,
                    'jenis_pelanggaran' => $p->jenisPelanggaran->nama_pelanggaran ?? '-',
                    'tanggal' => $p->created_at->format('d/m/Y')
                ];
            });
        return response()->json(['pelanggarans' => $pelanggarans]);
    });
    
    Route::middleware('role:admin,kesiswaan')->group(function () {
        Route::get('biodata-ortu', [\App\Http\Controllers\BiodataOrtuController::class, 'index'])->name('biodata-ortu.index');
        Route::get('biodata-ortu/{id}', [\App\Http\Controllers\BiodataOrtuController::class, 'show'])->name('biodata-ortu.show');
        Route::put('biodata-ortu/{id}/approve', [\App\Http\Controllers\BiodataOrtuController::class, 'approve'])->name('biodata-ortu.approve');
        Route::put('biodata-ortu/{id}/reject', [\App\Http\Controllers\BiodataOrtuController::class, 'reject'])->name('biodata-ortu.reject');
    });
    
    // Guru, Wali Kelas, BK, Admin, Kesiswaan Routes
    Route::middleware('role:admin,kesiswaan,guru,wali_kelas,bk')->group(function () {
        Route::resource('pelanggaran', PelanggaranController::class);
        Route::post('pelanggaran/{pelanggaran}/verify', [PelanggaranController::class, 'verify'])->name('pelanggaran.verify');
        Route::post('pelanggaran/{pelanggaran}/reject', [PelanggaranController::class, 'reject'])->name('pelanggaran.reject');
        Route::resource('prestasi', PrestasiController::class);
        Route::post('prestasi/{prestasi}/verify', [PrestasiController::class, 'verify'])->name('prestasi.verify')->middleware('role:admin,kesiswaan');
        Route::post('prestasi/{prestasi}/reject', [PrestasiController::class, 'reject'])->name('prestasi.reject')->middleware('role:admin,kesiswaan');
        Route::resource('bk', \App\Http\Controllers\BimbinganKonselingController::class);
        
        // Guru Mata Pelajaran Routes
        Route::get('guru/mata-pelajaran', [\App\Http\Controllers\GuruMataPelajaranController::class, 'edit'])->name('guru.mata-pelajaran.edit');
        Route::put('guru/mata-pelajaran', [\App\Http\Controllers\GuruMataPelajaranController::class, 'update'])->name('guru.mata-pelajaran.update');
    });
    
    // Wali Kelas Routes
    Route::prefix('wali-kelas')->name('wali-kelas.')->middleware('wali_kelas')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\WaliKelasController::class, 'dashboard'])->name('dashboard');
        Route::get('/siswa', [\App\Http\Controllers\WaliKelasController::class, 'siswa'])->name('siswa');
        Route::get('/siswa/{id}', [\App\Http\Controllers\WaliKelasController::class, 'siswaShow'])->name('siswa.show');
        Route::get('/pelanggaran/create', [\App\Http\Controllers\WaliKelasController::class, 'pelanggaranCreate'])->name('pelanggaran.create');
        Route::post('/pelanggaran/store', [\App\Http\Controllers\WaliKelasController::class, 'pelanggaranStore'])->name('pelanggaran.store');
        Route::get('/prestasi/create', [\App\Http\Controllers\WaliKelasController::class, 'prestasiCreate'])->name('prestasi.create');
        Route::post('/prestasi/store', [\App\Http\Controllers\WaliKelasController::class, 'prestasiStore'])->name('prestasi.store');
        Route::get('/komunikasi', [\App\Http\Controllers\WaliKelasController::class, 'komunikasi'])->name('komunikasi');
        Route::get('/laporan', [\App\Http\Controllers\WaliKelasController::class, 'laporanKelas'])->name('laporan');
    });
    
    // Orang Tua Routes
    Route::prefix('ortu')->name('ortu.')->middleware('role:ortu')->group(function () {
        Route::get('/pelanggaran', [\App\Http\Controllers\OrtuController::class, 'pelanggaran'])->name('pelanggaran');
        Route::get('/prestasi', [\App\Http\Controllers\OrtuController::class, 'prestasi'])->name('prestasi');
        Route::get('/sanksi', [\App\Http\Controllers\OrtuController::class, 'sanksi'])->name('sanksi');
        Route::get('/bimbingan', [\App\Http\Controllers\OrtuController::class, 'bimbingan'])->name('bimbingan');
        Route::get('/profil-anak', [\App\Http\Controllers\OrtuController::class, 'profil'])->name('profil');
    });
});