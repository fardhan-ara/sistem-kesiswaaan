# Panduan Verifikasi Email Laravel

## Alur Kerja Verifikasi Email

### 1. Interface MustVerifyEmail
```php
// app/Models/User.php
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    // Method untuk custom notification
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }
}
```

**Fungsi:**
- Menandai model User memerlukan verifikasi email
- Menambahkan method `hasVerifiedEmail()`, `markEmailAsVerified()`, `sendEmailVerificationNotification()`
- Field `email_verified_at` di database akan diisi saat email terverifikasi

---

### 2. Middleware 'verified'

**Cara Kerja:**
- Middleware `verified` memeriksa apakah `email_verified_at` tidak null
- Jika null, user diarahkan ke route `verification.notice`
- Jika sudah terverifikasi, user dapat mengakses route yang dilindungi

**Contoh Penggunaan:**

```php
// routes/web.php

// Melindungi single route
Route::get('/settings', function () {
    return view('settings.index');
})->middleware(['auth', 'verified'])->name('settings');

// Melindungi group routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('siswa', SiswaController::class);
    Route::resource('pelanggaran', PelanggaranController::class);
});
```

---

### 3. Routes Verifikasi Email

```php
// routes/web.php
use App\Http\Controllers\EmailVerificationController;

Route::middleware('auth')->group(function () {
    // Halaman notifikasi verifikasi
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
        ->name('verification.notice');
    
    // Endpoint untuk verifikasi (dari link email)
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware('signed')->name('verification.verify');
    
    // Kirim ulang email verifikasi
    Route::post('/email/resend', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')->name('verification.send');
});
```

**Penjelasan:**
- `verification.notice`: Halaman yang menampilkan pesan untuk verifikasi email
- `verification.verify`: Endpoint yang diakses dari link di email (signed URL untuk keamanan)
- `verification.send`: Endpoint untuk mengirim ulang email verifikasi (throttle 6 request per menit)

---

### 4. Controller Verifikasi Email

```php
// app/Http/Controllers/EmailVerificationController.php
class EmailVerificationController extends Controller
{
    // Tampilkan halaman notifikasi
    public function notice()
    {
        return view('auth.verify-email');
    }

    // Proses verifikasi dari link email
    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('dashboard')
            ->with('success', 'Email berhasil diverifikasi');
    }

    // Kirim ulang email verifikasi
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Link verifikasi telah dikirim ulang');
    }
}
```

---

### 5. Custom Email Template

```php
// app/Notifications/CustomVerifyEmail.php
namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Alamat Email - Sistem Kesiswaan')
            ->greeting('Halo ' . $notifiable->nama . '!')
            ->line('Terima kasih telah mendaftar di Sistem Kesiswaan.')
            ->line('Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda.')
            ->action('Verifikasi Email', $verificationUrl)
            ->line('Jika Anda tidak membuat akun, tidak ada tindakan lebih lanjut yang diperlukan.')
            ->salutation('Salam, Tim Sistem Kesiswaan');
    }
}
```

---

### 6. Proses Lengkap Verifikasi Email

**Step 1: User Registrasi**
```php
// AuthController.php - publicRegister()
$user = User::create([
    'nama' => $validated['nama'],
    'email' => $validated['email'],
    'password' => Hash::make($validated['password']),
    'role' => $validated['role'],
    'email_verified_at' => null  // Belum terverifikasi
]);

// Kirim email verifikasi
$user->sendEmailVerificationNotification();

// Login otomatis
Auth::login($user);

// Redirect ke halaman verifikasi
return redirect()->route('verification.notice');
```

**Step 2: Laravel Mengirim Email**
- Laravel memanggil `sendEmailVerificationNotification()` pada User model
- Notification `CustomVerifyEmail` dipanggil
- Email dikirim melalui queue atau langsung (tergantung konfigurasi)
- Email berisi signed URL: `/email/verify/{id}/{hash}`

**Step 3: User Klik Link di Email**
- User klik link verifikasi
- Request ke route `verification.verify`
- Middleware `signed` memvalidasi URL signature
- `EmailVerificationRequest` memvalidasi user dan hash
- Method `markEmailAsVerified()` mengisi `email_verified_at` dengan timestamp
- Event `Verified` di-trigger
- User diarahkan ke dashboard

**Step 4: Akses Route yang Dilindungi**
- User mencoba akses `/settings`
- Middleware `verified` memeriksa `email_verified_at`
- Jika terverifikasi: akses diberikan
- Jika belum: redirect ke `verification.notice`

---

## Konfigurasi Email (.env)

### Untuk Gmail:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Catatan Gmail:**
- Aktifkan 2-Factor Authentication
- Buat App Password di Google Account Settings
- Gunakan App Password, bukan password Gmail biasa

### Untuk Mailtrap (Testing):
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

---

## Troubleshooting: Email Tidak Terkirim

### 1. Cek Konfigurasi .env
```bash
php artisan config:clear
php artisan cache:clear
```

### 2. Test Koneksi Email
```bash
php artisan tinker
```
```php
Mail::raw('Test email', function($message) {
    $message->to('test@example.com')->subject('Test');
});
```

### 3. Cek Log Laravel
```bash
tail -f storage/logs/laravel.log
```

### 4. Gunakan Queue untuk Email
```env
QUEUE_CONNECTION=database
```

```bash
php artisan queue:table
php artisan migrate
php artisan queue:work
```

Update notification untuk queue:
```php
class CustomVerifyEmail extends VerifyEmail implements ShouldQueue
{
    use Queueable;
}
```

### 5. Cek Firewall/Port
- Pastikan port 587 (TLS) atau 465 (SSL) tidak diblokir
- Cek dengan: `telnet smtp.gmail.com 587`

### 6. Debugging Mode
```env
MAIL_MAILER=log
```
Email akan disimpan di `storage/logs/laravel.log`

---

## Testing Verifikasi Email

### 1. Manual Testing
```bash
# Registrasi user baru
# Cek email di inbox atau Mailtrap
# Klik link verifikasi
# Coba akses /settings
```

### 2. Unit Testing
```php
// tests/Feature/EmailVerificationTest.php
public function test_email_can_be_verified()
{
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    $this->assertTrue($user->fresh()->hasVerifiedEmail());
}
```

---

## Keamanan

### 1. Signed URLs
- Link verifikasi menggunakan signed URL
- Mencegah manipulasi parameter
- Expired setelah waktu tertentu

### 2. Throttling
- Limit pengiriman ulang email: 6 request per menit
- Mencegah spam

### 3. Hash Validation
- Hash email divalidasi untuk memastikan keaslian
- Mencegah verifikasi email orang lain

---

## Contoh Implementasi Lengkap

### Route Settings dengan Middleware Verified
```php
// routes/web.php
Route::get('/settings', function () {
    return view('settings.index');
})->middleware(['auth', 'verified'])->name('settings');
```

### View Settings
```blade
{{-- resources/views/settings/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Pengaturan Akun</div>
        <div class="card-body">
            <div class="alert alert-success">
                Email Anda telah diverifikasi!
            </div>
            
            <p><strong>Nama:</strong> {{ auth()->user()->nama }}</p>
            <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
            <p><strong>Status:</strong> 
                @if(auth()->user()->hasVerifiedEmail())
                    <span class="badge badge-success">Terverifikasi</span>
                @endif
            </p>
        </div>
    </div>
</div>
@endsection
```

### Jika User Belum Verifikasi
Saat user mencoba akses `/settings` tanpa verifikasi:
1. Middleware `verified` mendeteksi `email_verified_at` = null
2. User diarahkan ke `/email/verify` (verification.notice)
3. Halaman menampilkan pesan untuk cek email
4. User dapat klik "Kirim Ulang" jika email belum diterima

---

## Kesimpulan

**Alur Lengkap:**
1. User registrasi → `email_verified_at` = null
2. Laravel kirim email via `CustomVerifyEmail` notification
3. User klik link → route `verification.verify`
4. `markEmailAsVerified()` → `email_verified_at` = now()
5. User akses route dengan middleware `verified` → berhasil

**Komponen Utama:**
- `MustVerifyEmail` interface: Menandai model perlu verifikasi
- Middleware `verified`: Melindungi route
- `CustomVerifyEmail` notification: Template email
- `EmailVerificationController`: Handle verifikasi
- Signed URLs: Keamanan link verifikasi
