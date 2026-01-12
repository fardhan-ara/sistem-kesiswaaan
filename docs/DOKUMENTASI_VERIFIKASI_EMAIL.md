# Dokumentasi Verifikasi Email Laravel

## 📧 Alur Kerja Verifikasi Email

### 1. **Interface MustVerifyEmail**

Interface ini menandai bahwa User harus memverifikasi email sebelum mengakses fitur tertentu.

```php
// app/Models/User.php
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    // Model akan memiliki method:
    // - hasVerifiedEmail()
    // - markEmailAsVerified()
    // - sendEmailVerificationNotification()
    // - getEmailForVerification()
}
```

**Kolom Database yang Diperlukan:**
- `email_verified_at` (timestamp, nullable) - Menyimpan waktu verifikasi

### 2. **Proses Setelah User Mendaftar**

```
User Register → User Created → sendEmailVerificationNotification() 
→ CustomVerifyEmail Notification → Email Terkirim → User Klik Link 
→ Verifikasi Berhasil → email_verified_at Terisi
```

**Di AuthController.php:**
```php
public function publicRegister(Request $request)
{
    // 1. Validasi data
    $validated = $request->validate([...]);
    
    // 2. Buat user dengan email_verified_at = null
    $user = User::create([
        'nama' => $validated['nama'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        'email_verified_at' => null  // Belum terverifikasi
    ]);
    
    // 3. Kirim email verifikasi
    try {
        $user->sendEmailVerificationNotification();
    } catch (\Exception $e) {
        // Handle error
    }
    
    return redirect()->route('login')
        ->with('success', 'Pendaftaran berhasil. Silakan cek email untuk verifikasi.');
}
```

### 3. **Custom Email Notification**

```php
// app/Notifications/CustomVerifyEmail.php
namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    public function toMail($notifiable)
    {
        // Generate URL verifikasi dengan signature
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

**URL Verifikasi:**
- Format: `/email/verify/{id}/{hash}?expires={timestamp}&signature={signature}`
- Signed URL untuk keamanan
- Expire setelah 60 menit (default)

### 4. **Routes Verifikasi Email**

```php
// routes/web.php
Route::middleware('auth')->group(function () {
    // Halaman notice untuk user yang belum verifikasi
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
        ->name('verification.notice');
    
    // Endpoint untuk verifikasi (dari link email)
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware('signed')  // Validasi signature
        ->name('verification.verify');
    
    // Kirim ulang email verifikasi
    Route::post('/email/resend', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')  // Max 6 request per menit
        ->name('verification.send');
});
```

### 5. **Email Verification Controller**

```php
// app/Http/Controllers/EmailVerificationController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    // Tampilkan halaman notice
    public function notice()
    {
        return view('auth.verify-email');
    }
    
    // Proses verifikasi
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();  // Set email_verified_at = now()
        
        return redirect()->route('dashboard')
            ->with('success', 'Email berhasil diverifikasi!');
    }
    
    // Kirim ulang email
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }
        
        $request->user()->sendEmailVerificationNotification();
        
        return back()->with('success', 'Link verifikasi telah dikirim ulang!');
    }
}
```

### 6. **Middleware 'verified'**

Middleware ini melindungi route agar hanya bisa diakses user yang sudah verifikasi email.

**Cara Kerja:**
```php
// vendor/laravel/framework/src/Illuminate/Auth/Middleware/EnsureEmailIsVerified.php
public function handle($request, Closure $next, $redirectToRoute = null)
{
    if (! $request->user() ||
        ($request->user() instanceof MustVerifyEmail &&
        ! $request->user()->hasVerifiedEmail())) {
        
        // Redirect ke verification.notice
        return $request->expectsJson()
            ? abort(403, 'Your email address is not verified.')
            : Redirect::route($redirectToRoute ?: 'verification.notice');
    }

    return $next($request);
}
```

**Contoh Penggunaan:**

```php
// Melindungi route /settings
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

### 7. **Konfigurasi Email**

**Untuk Development (Log):**
```env
# .env
MAIL_MAILER=log
MAIL_FROM_ADDRESS="kesiswaan@sman1.sch.id"
MAIL_FROM_NAME="Sistem Kesiswaan"
```
Email akan tersimpan di `storage/logs/laravel.log`

**Untuk Production (SMTP - Gmail):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="kesiswaan@sman1.sch.id"
MAIL_FROM_NAME="Sistem Kesiswaan"
```

**Untuk Production (SMTP - Mailtrap Testing):**
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="kesiswaan@sman1.sch.id"
MAIL_FROM_NAME="Sistem Kesiswaan"
```

### 8. **View Verification Notice**

```blade
{{-- resources/views/auth/verify-email.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Verifikasi Email Anda</div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <p>Terima kasih telah mendaftar! Sebelum melanjutkan, silakan verifikasi alamat email Anda dengan mengklik link yang telah kami kirimkan.</p>
                    <p>Jika Anda tidak menerima email:</p>
                    
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            Kirim Ulang Email Verifikasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

## 🔧 Troubleshooting

### Email Tidak Terkirim?

1. **Cek Konfigurasi .env**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Cek Log File**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Test Koneksi SMTP**
   ```bash
   php artisan tinker
   Mail::raw('Test email', function($msg) {
       $msg->to('test@example.com')->subject('Test');
   });
   ```

4. **Pastikan Queue Berjalan (jika menggunakan queue)**
   ```bash
   php artisan queue:work
   ```

### User Sudah Register Tapi Belum Verifikasi?

```php
// Kirim ulang email verifikasi manual
$user = User::find(1);
$user->sendEmailVerificationNotification();
```

### Bypass Verifikasi untuk Testing

```php
// Di AuthController setelah create user
$user->markEmailAsVerified();  // Langsung verifikasi
```

## 📝 Checklist Implementasi

- [x] User model implements MustVerifyEmail
- [x] Database memiliki kolom email_verified_at
- [x] CustomVerifyEmail notification dibuat
- [x] Routes verifikasi email terdaftar
- [x] EmailVerificationController dibuat
- [x] Middleware 'verified' diterapkan pada routes
- [x] View verify-email.blade.php dibuat
- [x] Konfigurasi email di .env
- [ ] Test kirim email (gunakan Mailtrap untuk testing)

## 🚀 Cara Testing

1. **Register user baru**
2. **Cek log email** di `storage/logs/laravel.log`
3. **Copy URL verifikasi** dari log
4. **Akses URL** di browser
5. **Verifikasi berhasil** - email_verified_at terisi
6. **Akses route protected** - seharusnya bisa akses

## 📚 Referensi

- [Laravel Email Verification](https://laravel.com/docs/12.x/verification)
- [Laravel Mail](https://laravel.com/docs/12.x/mail)
- [Laravel Notifications](https://laravel.com/docs/12.x/notifications)
