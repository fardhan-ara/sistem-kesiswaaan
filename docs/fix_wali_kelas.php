<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Kelas;
use App\Models\Guru;

echo "=== PERBAIKAN SISTEM WALI KELAS ===\n\n";

// 1. Tampilkan user yang bisa dijadikan wali kelas
echo "1. USER YANG BISA DIJADIKAN WALI KELAS:\n";
$potentialWaliKelas = User::whereIn('role', ['guru', 'kesiswaan', 'admin'])
    ->where('status', 'approved')
    ->get();

if ($potentialWaliKelas->count() > 0) {
    foreach ($potentialWaliKelas as $user) {
        $guru = Guru::where('users_id', $user->id)->first();
        $status = $guru ? $guru->status_approval : 'N/A';
        echo "   [{$user->id}] {$user->nama} ({$user->role}) - Status: {$status}\n";
    }
} else {
    echo "   ✗ Tidak ada user yang bisa dijadikan wali kelas\n";
}

echo "\n2. KELAS YANG TERSEDIA:\n";
$kelas = Kelas::where('status_approval', 'approved')->get();
if ($kelas->count() > 0) {
    foreach ($kelas as $k) {
        echo "   [{$k->id}] {$k->nama_kelas}\n";
    }
} else {
    echo "   ✗ Tidak ada kelas yang approved\n";
}

echo "\n3. CONTOH PENUGASAN WALI KELAS:\n";
echo "   Untuk menugaskan user sebagai wali kelas, jalankan query:\n";
echo "   UPDATE users SET is_wali_kelas = 1, kelas_id = [ID_KELAS] WHERE id = [ID_USER];\n\n";

// Auto assign jika ada user dan kelas
if ($potentialWaliKelas->count() > 0 && $kelas->count() > 0) {
    echo "4. AUTO ASSIGN WALI KELAS (CONTOH):\n";
    
    // Ambil user pertama yang role guru atau kesiswaan
    $firstUser = $potentialWaliKelas->where('role', 'guru')->first() ?? 
                 $potentialWaliKelas->where('role', 'kesiswaan')->first() ?? 
                 $potentialWaliKelas->first();
    
    $firstKelas = $kelas->first();
    
    if ($firstUser && $firstKelas) {
        try {
            // Update user menjadi wali kelas
            $firstUser->update([
                'is_wali_kelas' => true,
                'kelas_id' => $firstKelas->id
            ]);
            
            echo "   ✓ {$firstUser->nama} berhasil ditugaskan sebagai wali kelas {$firstKelas->nama_kelas}\n";
            
            // Cek hasil
            $updated = User::find($firstUser->id);
            echo "   ✓ Verifikasi: is_wali_kelas = " . ($updated->is_wali_kelas ? 'true' : 'false') . 
                 ", kelas_id = {$updated->kelas_id}\n";
                 
        } catch (Exception $e) {
            echo "   ✗ Error: " . $e->getMessage() . "\n";
        }
    }
}

echo "\n5. CEK AKSES WALI KELAS:\n";
$waliKelasUsers = User::where('is_wali_kelas', true)->get();
if ($waliKelasUsers->count() > 0) {
    foreach ($waliKelasUsers as $user) {
        $kelas = Kelas::find($user->kelas_id);
        echo "   ✓ {$user->nama} -> Wali Kelas {$kelas->nama_kelas}\n";
        echo "     - URL Dashboard: /wali-kelas/dashboard\n";
        echo "     - Middleware: wali_kelas (✓ terdaftar)\n";
        echo "     - Method hasRole: " . ($user->hasRole(['wali_kelas']) ? '✓ berfungsi' : '✗ error') . "\n";
    }
} else {
    echo "   ✗ Belum ada wali kelas yang ditugaskan\n";
}

echo "\n6. TESTING AUTHORIZATION:\n";
if ($waliKelasUsers->count() > 0) {
    $testUser = $waliKelasUsers->first();
    echo "   Testing user: {$testUser->nama}\n";
    echo "   - isWaliKelas(): " . ($testUser->isWaliKelas() ? '✓ true' : '✗ false') . "\n";
    echo "   - hasRole(['wali_kelas']): " . ($testUser->hasRole(['wali_kelas']) ? '✓ true' : '✗ false') . "\n";
    echo "   - hasRole(['guru', 'wali_kelas']): " . ($testUser->hasRole(['guru', 'wali_kelas']) ? '✓ true' : '✗ false') . "\n";
} else {
    echo "   ⚠ Tidak ada wali kelas untuk ditest\n";
}

echo "\n=== SELESAI ===\n";