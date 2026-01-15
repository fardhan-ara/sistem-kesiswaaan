<?php

/**
 * Script Diagnosa: Masalah Submit Pelanggaran
 * 
 * Cara pakai:
 * php artisan tinker
 * include 'diagnosa_pelanggaran.php';
 * diagnosaPelanggaran('email@user.com');
 */

function diagnosaPelanggaran($email) {
    echo "\n";
    echo "╔════════════════════════════════════════════════════════════╗\n";
    echo "║     DIAGNOSA MASALAH SUBMIT PELANGGARAN                    ║\n";
    echo "╚════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    
    $user = \App\Models\User::where('email', $email)->first();
    
    if (!$user) {
        echo "❌ ERROR: User dengan email '$email' tidak ditemukan!\n";
        return;
    }
    
    echo "✅ User ditemukan: {$user->nama}\n";
    echo "   Email: {$user->email}\n";
    echo "   Role: {$user->role}\n";
    echo "   Status: {$user->status}\n";
    echo "\n";
    
    // Cek 1: Role
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "1️⃣  CEK ROLE & PERMISSION\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $allowedRoles = ['admin', 'kesiswaan', 'guru', 'wali_kelas', 'bk'];
    $hasPermission = in_array($user->role, $allowedRoles) || $user->is_wali_kelas;
    
    if ($hasPermission) {
        echo "✅ Role VALID - User bisa submit pelanggaran\n";
    } else {
        echo "❌ Role INVALID - User TIDAK bisa submit pelanggaran\n";
        echo "   Role saat ini: {$user->role}\n";
        echo "   Role yang diizinkan: " . implode(', ', $allowedRoles) . "\n";
        echo "\n";
        echo "   💡 SOLUSI:\n";
        echo "   \$user = User::find({$user->id});\n";
        echo "   \$user->role = 'guru'; // atau admin, kesiswaan\n";
        echo "   \$user->save();\n";
    }
    echo "\n";
    
    // Cek 2: Koneksi dengan Guru
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "2️⃣  CEK KONEKSI USER → GURU\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $guru = \App\Models\Guru::where('users_id', $user->id)->first();
    
    if ($guru) {
        echo "✅ User TERHUBUNG dengan data guru\n";
        echo "   Guru ID: {$guru->id}\n";
        echo "   NIP: {$guru->nip}\n";
        echo "   Nama: {$guru->nama_guru}\n";
        echo "   Bidang Studi: {$guru->bidang_studi}\n";
        echo "   Status: {$guru->status}\n";
    } else {
        echo "❌ User TIDAK TERHUBUNG dengan data guru\n";
        echo "   Ini adalah masalah UTAMA!\n";
        echo "\n";
        echo "   💡 SOLUSI:\n";
        echo "   Guru::create([\n";
        echo "       'users_id' => {$user->id},\n";
        echo "       'nip' => 'AUTO-{$user->id}',\n";
        echo "       'nama_guru' => '{$user->nama}',\n";
        echo "       'bidang_studi' => 'Umum',\n";
        echo "       'status' => 'aktif'\n";
        echo "   ]);\n";
    }
    echo "\n";
    
    // Cek 3: Data Master
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "3️⃣  CEK DATA MASTER\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $jumlahSiswa = \App\Models\Siswa::count();
    $jumlahGuru = \App\Models\Guru::count();
    $jumlahJenisPelanggaran = \App\Models\JenisPelanggaran::count();
    $jumlahTahunAjaran = \App\Models\TahunAjaran::count();
    
    echo "Siswa: " . ($jumlahSiswa > 0 ? "✅ $jumlahSiswa data" : "❌ KOSONG") . "\n";
    echo "Guru: " . ($jumlahGuru > 0 ? "✅ $jumlahGuru data" : "❌ KOSONG") . "\n";
    echo "Jenis Pelanggaran: " . ($jumlahJenisPelanggaran > 0 ? "✅ $jumlahJenisPelanggaran data" : "❌ KOSONG") . "\n";
    echo "Tahun Ajaran: " . ($jumlahTahunAjaran > 0 ? "✅ $jumlahTahunAjaran data" : "❌ KOSONG") . "\n";
    
    if ($jumlahSiswa == 0 || $jumlahGuru == 0 || $jumlahJenisPelanggaran == 0) {
        echo "\n";
        echo "   💡 SOLUSI: Jalankan seeder\n";
        echo "   php artisan db:seed\n";
    }
    echo "\n";
    
    // Cek 4: Route
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "4️⃣  CEK ROUTE\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $pelanggaranStoreRoute = null;
    
    foreach ($routes as $route) {
        if ($route->uri() == 'pelanggaran' && in_array('POST', $route->methods())) {
            $pelanggaranStoreRoute = $route;
            break;
        }
    }
    
    if ($pelanggaranStoreRoute) {
        echo "✅ Route POST /pelanggaran DITEMUKAN\n";
        echo "   Controller: " . $pelanggaranStoreRoute->getActionName() . "\n";
    } else {
        echo "❌ Route POST /pelanggaran TIDAK DITEMUKAN\n";
        echo "   Cek file routes/web.php\n";
    }
    echo "\n";
    
    // Cek 5: Test Insert
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "5️⃣  TEST INSERT DATABASE\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    try {
        $siswa = \App\Models\Siswa::first();
        $guruTest = \App\Models\Guru::first();
        $jenisPelanggaran = \App\Models\JenisPelanggaran::first();
        $tahunAjaran = \App\Models\TahunAjaran::first();
        
        if (!$siswa || !$guruTest || !$jenisPelanggaran) {
            echo "❌ Tidak bisa test - data master kosong\n";
        } else {
            // Test insert (rollback)
            \DB::beginTransaction();
            
            $testPelanggaran = \App\Models\Pelanggaran::create([
                'siswa_id' => $siswa->id,
                'guru_pencatat' => $guruTest->id,
                'jenis_pelanggaran_id' => $jenisPelanggaran->id,
                'tahun_ajaran_id' => $tahunAjaran ? $tahunAjaran->id : 1,
                'poin' => $jenisPelanggaran->poin,
                'tanggal_pelanggaran' => now(),
                'status_verifikasi' => 'menunggu'
            ]);
            
            \DB::rollBack(); // Rollback test data
            
            echo "✅ Test insert BERHASIL\n";
            echo "   Database connection OK\n";
            echo "   Model Pelanggaran OK\n";
        }
    } catch (\Exception $e) {
        echo "❌ Test insert GAGAL\n";
        echo "   Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // Kesimpulan
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📊 KESIMPULAN\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $masalah = [];
    
    if (!$hasPermission) {
        $masalah[] = "Role user tidak valid";
    }
    
    if (!$guru) {
        $masalah[] = "User tidak terhubung dengan data guru";
    }
    
    if ($jumlahSiswa == 0) {
        $masalah[] = "Data siswa kosong";
    }
    
    if ($jumlahJenisPelanggaran == 0) {
        $masalah[] = "Data jenis pelanggaran kosong";
    }
    
    if (empty($masalah)) {
        echo "✅ TIDAK ADA MASALAH DITEMUKAN\n";
        echo "\n";
        echo "Jika masih tidak bisa submit, coba:\n";
        echo "1. Clear cache: php artisan cache:clear\n";
        echo "2. Clear browser cache (Ctrl+Shift+Del)\n";
        echo "3. Cek browser console (F12) untuk error JavaScript\n";
        echo "4. Cek file: storage/logs/laravel.log\n";
    } else {
        echo "❌ DITEMUKAN " . count($masalah) . " MASALAH:\n";
        foreach ($masalah as $i => $m) {
            echo "   " . ($i + 1) . ". $m\n";
        }
        echo "\n";
        echo "📖 Lihat file: PERBAIKAN_SUBMIT_PELANGGARAN.md\n";
        echo "   untuk solusi lengkap\n";
    }
    
    echo "\n";
    echo "╔════════════════════════════════════════════════════════════╗\n";
    echo "║                    DIAGNOSA SELESAI                        ║\n";
    echo "╚════════════════════════════════════════════════════════════╝\n";
    echo "\n";
}

// Jika dijalankan langsung
if (php_sapi_name() === 'cli' && isset($argv[1])) {
    diagnosaPelanggaran($argv[1]);
}
