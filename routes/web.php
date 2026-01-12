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

// Welcome page - Landing Page (PATEN - TIDAK BOLEH REDIRECT)
Route::get('/', [AuthController::class, 'showLanding'])->name('landing');
Route::get('/welcome', [AuthController::class, 'showLanding'])->name('welcome');

// Test authentication flow
Route::get('/test-auth-flow', function() {
    if (!Auth::check()) {
        return 'Not logged in - redirect to login';
    }
    
    $user = Auth::user();
    $redirectUrl = '';
    
    switch ($user->role) {
        case 'admin':
            $redirectUrl = route('dashboard') . ' (Admin Dashboard)';
            break;
        case 'kesiswaan':
            $redirectUrl = route('dashboard') . ' (Kesiswaan Dashboard)';
            break;
        case 'guru':
            if ($user->is_wali_kelas) {
                $redirectUrl = route('wali-kelas.dashboard') . ' (Wali Kelas Dashboard)';
            } else {
                $redirectUrl = route('dashboard') . ' (Guru Dashboard)';
            }
            break;
        case 'wali_kelas':
            $redirectUrl = route('wali-kelas.dashboard') . ' (Wali Kelas Dashboard)';
            break;
        case 'siswa':
            $redirectUrl = route('dashboard') . ' (Siswa Dashboard)';
            break;
        case 'ortu':
            $redirectUrl = route('dashboard') . ' (Ortu Dashboard)';
            break;
        default:
            $redirectUrl = route('dashboard') . ' (Default Dashboard)';
    }
    
    return response()->json([
        'user' => $user->nama,
        'role' => $user->role,
        'is_wali_kelas' => $user->is_wali_kelas ?? false,
        'status' => $user->status,
        'redirect_url' => $redirectUrl,
        'message' => 'Authentication flow working correctly'
    ]);
})->middleware('auth');

// Debug routes  
Route::post('/test-store-pelanggaran', function(\Illuminate\Http\Request $request) {
    try {
        \Log::info('TEST ROUTE CALLED', $request->all());
        
        $siswa = \App\Models\Siswa::findOrFail($request->siswa_id);
        $guru = \App\Models\Guru::findOrFail($request->guru_pencatat);
        $jenis = \App\Models\JenisPelanggaran::findOrFail($request->jenis_pelanggaran_id);
        
        $p = \App\Models\Pelanggaran::create([
            'siswa_id' => $siswa->id,
            'guru_pencatat' => $guru->id,
            'jenis_pelanggaran_id' => $jenis->id,
            'tahun_ajaran_id' => 1,
            'poin' => $jenis->poin,
            'tanggal_pelanggaran' => now(),
            'status_verifikasi' => 'menunggu',
            'keterangan' => $request->keterangan
        ]);
        
        return redirect('/pelanggaran')->with('success', 'TEST SUCCESS! ID: ' . $p->id);
    } catch (\Exception $e) {
        return redirect('/pelanggaran')->with('error', 'TEST ERROR: ' . $e->getMessage());
    }
})->middleware('auth');

Route::get('/test-pelanggaran', function() {
    try {
        $pelanggarans = \App\Models\Pelanggaran::with(['siswa', 'jenisPelanggaran'])->paginate(20);
        $statistik = ['total' => 0, 'menunggu' => 0, 'terverifikasi' => 0, 'ditolak' => 0];
        return view('pelanggaran.index', compact('pelanggarans', 'statistik'));
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage() . '<br>File: ' . $e->getFile() . '<br>Line: ' . $e->getLine();
    }
})->middleware('auth');

Route::get('/test-dashboard', function() {
    try {
        $controller = new \App\Http\Controllers\DashboardController();
        return $controller->index();
    } catch (\Exception $e) {
        return 'Dashboard Error: ' . $e->getMessage() . '<br>File: ' . $e->getFile() . '<br>Line: ' . $e->getLine() . '<br><br>Trace:<br>' . nl2br($e->getTraceAsString());
    }
})->middleware('auth');

Route::get('/test-direct-pelanggaran', function() {
    return 'Direct route works! Now testing controller...<br><br>' . 
           'User: ' . auth()->user()->nama . '<br>' .
           'Role: ' . auth()->user()->role . '<br>' .
           'Pelanggaran count: ' . \App\Models\Pelanggaran::count() . '<br><br>' .
           '<form action="/test-store-pelanggaran" method="POST">' .
           csrf_field() .
           'Siswa: <select name="siswa_id">' . \App\Models\Siswa::all()->map(fn($s) => '<option value="'.$s->id.'">'.$s->nama_siswa.'</option>')->implode('') . '</select><br>' .
           'Guru: <select name="guru_pencatat">' . \App\Models\Guru::all()->map(fn($g) => '<option value="'.$g->id.'">'.$g->nama_guru.'</option>')->implode('') . '</select><br>' .
           'Jenis: <select name="jenis_pelanggaran_id">' . \App\Models\JenisPelanggaran::take(10)->get()->map(fn($j) => '<option value="'.$j->id.'">'.$j->nama_pelanggaran.'</option>')->implode('') . '</select><br>' .
           '<button type="submit">TEST SUBMIT</button></form>';
})->middleware('auth');



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
        Route::resource('kelas', \App\Http\Controllers\KelasController::class);
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
    
    // Debug route untuk cek akses siswa
    Route::get('/debug-siswa', function() {
        $user = auth()->user();
        return response()->json([
            'user' => $user->nama,
            'role' => $user->role,
            'can_access_siswa' => in_array($user->role, ['admin', 'kesiswaan']),
            'siswa_count' => \App\Models\Siswa::count()
        ]);
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
        
        // TEST BACKUP DEBUG
        Route::get('test-backup-debug', function() {
            $disk = Storage::disk('local');
            $backupPath = 'private/sistem-kesiswaan';
            $exists = $disk->exists($backupPath);
            $files = $disk->exists($backupPath) ? $disk->files($backupPath) : [];
            $backups = collect($files)->filter(fn($f) => str_ends_with($f, '.zip'))->map(function ($file) use ($disk) {
                return [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => $disk->size($file),
                    'date' => date('d/m/Y H:i:s', $disk->lastModified($file)),
                ];
            })->values();
            return response()->json([
                'backup_path' => $backupPath,
                'exists' => $exists,
                'files_count' => count($files),
                'files' => $files,
                'backups' => $backups
            ]);
        });
        
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
    
    // Test route biodata
    Route::get('test-biodata', function() {
        $user = auth()->user();
        return response()->json([
            'user' => $user->nama,
            'role' => $user->role,
            'can_access' => in_array($user->role, ['admin', 'kesiswaan']),
            'biodata_count' => \App\Models\BiodataOrtu::count()
        ]);
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
        Route::post('pelanggaran/{pelanggaran}/verify', [PelanggaranController::class, 'verify'])->name('pelanggaran.verify')->middleware('role:admin,kesiswaan');
        Route::post('pelanggaran/{pelanggaran}/reject', [PelanggaranController::class, 'reject'])->name('pelanggaran.reject')->middleware('role:admin,kesiswaan');
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
    
    // TEST ROUTE - VERIFY
    Route::post('/pelanggaran-verify-test/{id}', function($id) {
        try {
            $pelanggaran = \App\Models\Pelanggaran::findOrFail($id);
            $pelanggaran->update([
                'status_verifikasi' => 'diverifikasi',
                'tanggal_verifikasi' => now()
            ]);
            return redirect('/pelanggaran')->with('success', 'Pelanggaran berhasil disetujui!');
        } catch (\Exception $e) {
            return redirect('/pelanggaran')->with('error', 'Gagal: ' . $e->getMessage());
        }
    });
    
    // TEST ROUTE - REJECT
    Route::post('/pelanggaran-reject-test/{id}', function($id, \Illuminate\Http\Request $request) {
        try {
            $pelanggaran = \App\Models\Pelanggaran::findOrFail($id);
            $pelanggaran->update([
                'status_verifikasi' => 'ditolak',
                'tanggal_verifikasi' => now(),
                'alasan_penolakan' => $request->alasan_penolakan
            ]);
            return redirect('/pelanggaran')->with('success', 'Pelanggaran berhasil ditolak!');
        } catch (\Exception $e) {
            return redirect('/pelanggaran')->with('error', 'Gagal: ' . $e->getMessage());
        }
    });
    
    // TEST ROUTE - DELETE
    Route::post('/pelanggaran-delete-test/{id}', function($id) {
        \Log::info('=== DELETE TEST ===', ['id' => $id]);
        try {
            $pelanggaran = \App\Models\Pelanggaran::findOrFail($id);
            $pelanggaran->delete();
            \Log::info('Delete success');
            return redirect('/pelanggaran')->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            \Log::error('Delete error:', ['msg' => $e->getMessage()]);
            return redirect('/pelanggaran')->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    });
    
    // TEST ROUTE - DIRECT UPDATE
    Route::post('/pelanggaran-update-test/{id}', function($id, \Illuminate\Http\Request $request) {
        \Log::info('=== TEST UPDATE START ===', ['id' => $id]);
        
        try {
            $pelanggaran = \App\Models\Pelanggaran::findOrFail($id);
            \Log::info('Pelanggaran found:', ['id' => $pelanggaran->id, 'poin_awal' => $pelanggaran->poin]);
            
            // Ambil list pelanggaran yang sudah ada
            $pelanggaranList = $pelanggaran->pelanggaran_list ? json_decode($pelanggaran->pelanggaran_list, true) : [];
            
            // Tambah pelanggaran baru ke list
            $totalPoinTambahan = 0;
            if ($request->has('pelanggaran_tambahan') && is_array($request->pelanggaran_tambahan)) {
                foreach ($request->pelanggaran_tambahan as $jenisId) {
                    $jenis = \App\Models\JenisPelanggaran::find($jenisId);
                    $pelanggaranList[] = [
                        'id' => $jenis->id,
                        'nama' => $jenis->nama_pelanggaran,
                        'poin' => $jenis->poin
                    ];
                    $totalPoinTambahan += $jenis->poin;
                }
            }
            
            // Update pelanggaran
            $pelanggaran->poin = $pelanggaran->poin + $totalPoinTambahan;
            $pelanggaran->keterangan = $request->keterangan;
            $pelanggaran->pelanggaran_list = json_encode($pelanggaranList);
            $pelanggaran->save();
            
            \Log::info('=== UPDATE SUCCESS ===', ['poin_final' => $pelanggaran->poin]);
            
            $message = 'Data berhasil diupdate!';
            if ($totalPoinTambahan > 0) {
                $message .= ' Poin bertambah +' . $totalPoinTambahan . ' (Total: ' . $pelanggaran->poin . ')';
            }
            
            return redirect('/pelanggaran')->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('=== UPDATE ERROR ===', ['msg' => $e->getMessage()]);
            return redirect('/pelanggaran')->with('error', 'ERROR: ' . $e->getMessage());
        }
    });
    
    // TEST ROUTE - HAPUS ITEM PELANGGARAN
    Route::post('/pelanggaran-hapus-item-test/{id}/{index}', function($id, $index) {
        \Log::info('=== HAPUS ITEM TEST ===', ['id' => $id, 'index' => $index]);
        
        try {
            $pelanggaran = \App\Models\Pelanggaran::findOrFail($id);
            $pelanggaranList = $pelanggaran->pelanggaran_list ? json_decode($pelanggaran->pelanggaran_list, true) : [];
            
            if (isset($pelanggaranList[$index])) {
                $poinDihapus = $pelanggaranList[$index]['poin'];
                unset($pelanggaranList[$index]);
                $pelanggaranList = array_values($pelanggaranList);
                
                $pelanggaran->poin = $pelanggaran->poin - $poinDihapus;
                $pelanggaran->pelanggaran_list = json_encode($pelanggaranList);
                $pelanggaran->save();
                
                return redirect()->back()->with('success', 'Pelanggaran berhasil dihapus! Poin berkurang -' . $poinDihapus);
            }
            
            return redirect()->back()->with('error', 'Item tidak ditemukan');
        } catch (\Exception $e) {
            \Log::error('=== HAPUS ITEM ERROR ===', ['msg' => $e->getMessage()]);
            return redirect()->back()->with('error', 'ERROR: ' . $e->getMessage());
        }
    });
    
    // TEST ROUTE PRESTASI - STORE
    Route::post('/prestasi-store-test', function(\Illuminate\Http\Request $request) {
        \Log::info('=== PRESTASI STORE TEST ===', $request->all());
        try {
            $siswa = \App\Models\Siswa::findOrFail($request->siswa_id);
            $guru = \App\Models\Guru::findOrFail($request->guru_pencatat);
            $jenis = \App\Models\JenisPrestasi::findOrFail($request->jenis_prestasi_id);
            
            $prestasi = \App\Models\Prestasi::create([
                'siswa_id' => $siswa->id,
                'guru_pencatat' => $guru->id,
                'jenis_prestasi_id' => $jenis->id,
                'tahun_ajaran_id' => $siswa->tahun_ajaran_id ?? 1,
                'poin' => $jenis->poin_reward,
                'tanggal_prestasi' => now(),
                'status_verifikasi' => 'pending',
                'keterangan' => $request->keterangan
            ]);
            
            \Log::info('Prestasi created', ['id' => $prestasi->id]);
            return redirect('/prestasi')->with('success', 'Prestasi berhasil ditambahkan!');
        } catch (\Exception $e) {
            \Log::error('Prestasi store error', ['msg' => $e->getMessage()]);
            return redirect('/prestasi/create')->with('error', 'ERROR: ' . $e->getMessage());
        }
    });
    
    // TEST ROUTE PRESTASI - CREATE
    Route::get('/prestasi-create-test', function() {
        try {
            $siswas = \App\Models\Siswa::with('kelas')->orderBy('nama_siswa')->get();
            $gurus = \App\Models\Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
            $jenisPrestasis = \App\Models\JenisPrestasi::orderBy('tingkat')->orderBy('nama_prestasi')->get();
            $tingkats = \App\Models\JenisPrestasi::select('tingkat')->distinct()->pluck('tingkat');
            $kategoriPenampilans = \App\Models\JenisPrestasi::select('kategori_penampilan')->distinct()->pluck('kategori_penampilan');
            return view('prestasi.create', compact('siswas', 'gurus', 'jenisPrestasis', 'tingkats', 'kategoriPenampilans'));
        } catch (\Exception $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    });
});