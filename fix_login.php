<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== FIX LOGIN ISSUES ===\n\n";

// Cek semua user
$users = User::all();
echo "Total users: " . $users->count() . "\n\n";

foreach ($users as $user) {
    echo "Email: {$user->email}\n";
    echo "Role: {$user->role}\n";
    echo "Status: {$user->status}\n";
    echo "Password Hash: " . substr($user->password, 0, 20) . "...\n";
    
    // Cek apakah password 'password' bisa login
    if (Hash::check('password', $user->password)) {
        echo "✓ Password 'password' VALID\n";
    } else {
        echo "✗ Password 'password' TIDAK VALID - Mereset...\n";
        $user->password = Hash::make('password');
        $user->save();
        echo "✓ Password direset ke 'password'\n";
    }
    
    // Pastikan status approved untuk testing
    if ($user->status !== 'approved' && $user->role !== 'ortu') {
        echo "! Status bukan approved, mengubah ke approved...\n";
        $user->status = 'approved';
        $user->save();
        echo "✓ Status diubah ke approved\n";
    }
    
    echo "\n";
}

echo "\n=== SELESAI ===\n";
echo "Silakan coba login dengan:\n";
echo "Email: admin@test.com\n";
echo "Password: password\n";
