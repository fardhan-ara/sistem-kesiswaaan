<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Password;

$email = 'siswa@test.com';

$user = User::where('email', $email)->first();
if (!$user) {
    $user = User::create([
        'nama' => 'Test Siswa',
        'email' => $email,
        'password' => bcrypt('password'),
        'role' => 'siswa',
    ]);
    echo "Created test user: {$email}\n";
}

$token = Password::broker()->createToken($user);
$link = "http://127.0.0.1:8000/reset-password/" . $token . "?email=" . urlencode($user->email);

echo "RESET LINK: " . $link . "\n";
