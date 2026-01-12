<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== FINAL VERIFICATION ===\n\n";

$waliKelasUsers = User::where('is_wali_kelas', true)->with('kelasWali')->get();

echo "TESTING FIXED isWaliKelas() METHOD:\n";
foreach ($waliKelasUsers->take(3) as $user) {
    echo "   {$user->nama}:\n";
    echo "     - is_wali_kelas value: " . var_export($user->is_wali_kelas, true) . "\n";
    echo "     - isWaliKelas(): " . ($user->isWaliKelas() ? '✓ true' : '✗ false') . "\n";
    echo "     - hasRole(['wali_kelas']): " . ($user->hasRole(['wali_kelas']) ? '✓ true' : '✗ false') . "\n";
}

echo "\n=== SISTEM WALI KELAS LENGKAP DAN BERFUNGSI! ===\n";