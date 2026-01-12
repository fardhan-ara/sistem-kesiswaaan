<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Kelas;

echo "=== ANALISIS SISTEM WALI KELAS ===\n\n";

// 1. Cek struktur tabel users
echo "1. CEK KOLOM WALI KELAS DI TABEL USERS:\n";
try {
    $user = User::first();
    if ($user) {
        $attributes = $user->getAttributes();
        $hasWaliKelas = array_key_exists('is_wali_kelas', $attributes);
        $hasKelasId = array_key_exists('kelas_id', $attributes);
        
        echo "   - is_wali_kelas: " . ($hasWaliKelas ? "✓ ADA" : "✗ TIDAK ADA") . "\n";
        echo "   - kelas_id: " . ($hasKelasId ? "✓ ADA" : "✗ TIDAK ADA") . "\n";
    } else {
        echo "   ✗ Tidak ada user di database\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n2. CEK USER DENGAN STATUS WALI KELAS:\n";
try {
    $waliKelasUsers = User::where('is_wali_kelas', true)->get();
    echo "   Total wali kelas: " . $waliKelasUsers->count() . "\n";
    
    if ($waliKelasUsers->count() > 0) {
        foreach ($waliKelasUsers as $user) {
            $kelas = $user->kelas_id ? Kelas::find($user->kelas_id) : null;
            echo "   - {$user->nama} ({$user->role}) -> Kelas: " . ($kelas ? $kelas->nama_kelas : 'TIDAK ADA') . "\n";
        }
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n3. CEK KELAS YANG TERSEDIA:\n";
try {
    $kelas = Kelas::all();
    echo "   Total kelas: " . $kelas->count() . "\n";
    
    if ($kelas->count() > 0) {
        foreach ($kelas->take(5) as $k) {
            $waliKelas = User::where('kelas_id', $k->id)->where('is_wali_kelas', true)->first();
            echo "   - {$k->nama_kelas} -> Wali Kelas: " . ($waliKelas ? $waliKelas->nama : 'BELUM ADA') . "\n";
        }
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n4. CEK MIDDLEWARE DAN ROUTES:\n";
try {
    $middlewareFile = file_exists('app/Http/Middleware/CheckWaliKelas.php');
    echo "   - CheckWaliKelas middleware: " . ($middlewareFile ? "✓ ADA" : "✗ TIDAK ADA") . "\n";
    
    $routeContent = file_get_contents('routes/web.php');
    $hasWaliKelasRoutes = strpos($routeContent, 'wali-kelas') !== false;
    echo "   - Wali kelas routes: " . ($hasWaliKelasRoutes ? "✓ ADA" : "✗ TIDAK ADA") . "\n";
    
    $hasMiddlewareAlias = strpos($routeContent, 'wali_kelas') !== false;
    echo "   - Middleware alias: " . ($hasMiddlewareAlias ? "✓ ADA" : "✗ TIDAK ADA") . "\n";
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n5. CEK CONTROLLER WALI KELAS:\n";
try {
    $controllerFile = file_exists('app/Http/Controllers/WaliKelasController.php');
    echo "   - WaliKelasController: " . ($controllerFile ? "✓ ADA" : "✗ TIDAK ADA") . "\n";
    
    if ($controllerFile) {
        $controllerContent = file_get_contents('app/Http/Controllers/WaliKelasController.php');
        $methods = ['dashboard', 'siswa', 'pelanggaranCreate', 'prestasiCreate', 'komunikasi'];
        foreach ($methods as $method) {
            $hasMethod = strpos($controllerContent, "function {$method}") !== false;
            echo "   - Method {$method}: " . ($hasMethod ? "✓ ADA" : "✗ TIDAK ADA") . "\n";
        }
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n6. REKOMENDASI PERBAIKAN:\n";

// Cek apakah ada masalah
$issues = [];

try {
    $waliKelasCount = User::where('is_wali_kelas', true)->count();
    if ($waliKelasCount == 0) {
        $issues[] = "Tidak ada user yang ditugaskan sebagai wali kelas";
    }
    
    $kelasCount = Kelas::count();
    if ($kelasCount == 0) {
        $issues[] = "Tidak ada data kelas";
    }
    
    $userCount = User::count();
    if ($userCount == 0) {
        $issues[] = "Tidak ada user di sistem";
    }
} catch (Exception $e) {
    $issues[] = "Error database: " . $e->getMessage();
}

if (empty($issues)) {
    echo "   ✓ Sistem wali kelas sudah siap!\n";
    echo "\n7. CARA MENGAKTIFKAN WALI KELAS:\n";
    echo "   UPDATE users SET is_wali_kelas = 1, kelas_id = [ID_KELAS] WHERE id = [ID_USER];\n";
} else {
    echo "   MASALAH YANG DITEMUKAN:\n";
    foreach ($issues as $issue) {
        echo "   ✗ {$issue}\n";
    }
}

echo "\n=== SELESAI ===\n";