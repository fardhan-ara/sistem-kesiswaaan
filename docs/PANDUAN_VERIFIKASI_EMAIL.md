# Panduan Lengkap Verifikasi Email Laravel

## 📋 Daftar Isi
1. [Alur Kerja Verifikasi Email](#alur-kerja)
2. [Konfigurasi Email](#konfigurasi-email)
3. [Testing Email](#testing-email)
4. [Troubleshooting](#troubleshooting)
5. [Contoh Penggunaan Middleware](#middleware)

---

## 🔄 Alur Kerja Verifikasi Email

### 1. Interface MustVerifyEmail
Model User harus implement interface ini:

```php
// app/Models/User.php
class User extends Authenticatable implements MustVerifyEmail
{
    // ...
}
```

### 2. Proses Registrasi
Ketika user mendaftar:
- User dibuat dengan `email_verified_at = null`
- Event `Registered` di-trigger
- Email verifikasi dikirim otomatis
- User di-redirect ke halaman notice

### 3. Email Verifikasi
Laravel mengirim email dengan:
- Link verifikasi yang signed (aman)
- Token yang expire dalam 60 menit
- Template custom dari `CustomVerifyEmail`

### 4. Middleware 'verified'
Melindungi route yang hanya bisa diakses user terverifikasi:

```php
Route::get('/settings', function () {
    return view('settings.index');
})->middleware(['auth', 'verified']);
```

---

## ⚙️ Konfigurasi Email

### Opsi 1: Gmail (Production)

1. **Aktifkan 2-Step Verification**
   - Buka: https://myaccount.google.com/security
   - Aktifkan "2-Step Verification"

2. **Generate App Password**
   - Buka: https://myaccount.google.com/apppasswords
   - Pilih "Mail" dan "Other (Custom name)"
   - Nama: "Sistem Kesiswaan"
   - Copy password 16 digit

3. **Update .env**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Sistem Kesiswaan"
```

### Opsi 2: Mailtrap (Testing)

1. **Daftar di Mailtrap.io**
   - Buka: https://mailtrap.io
   - Buat akun gratis

2. **Copy Credentials**
   - Buka inbox Anda
   - Copy SMTP credentials

3. **Update .env**
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=test@example.com
MAIL_FROM_NAME="Sistem Kesiswaan"
```

### Opsi 3: Log (Development)

```env
MAIL_MAILER=log
```
Email akan disimpan di `storage/logs/laravel.log`

---

## 🧪 Testing Email

### 1. Test Koneksi Email
```bash
php artisan email:test your-email@gmail.com
```

### 2. Test Verifikasi Email Manual
```bash
php artisan tinker
```
```php
$user = User::find(1);
$user->sendEmailVerificationNotification();
```

### 3. Cek Log
```bash
tail -f storage/logs/laravel.log
```

### 4. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## 🐛 Troubleshooting

### Email Tidak Terkirim

**1. Cek Konfigurasi**
```bash
php artisan config:show mail
```

**2. Cek Queue**
Jika menggunakan queue:
```bash
# Cek apakah ada job pending
php artisan queue:work

# Atau jalankan sekali
php artisan queue:work --once
```

**3. Cek Firewall**
- Port 587 (TLS) atau 465 (SSL) harus terbuka
- Disable antivirus sementara untuk test

**4. Gmail Specific**
- Pastikan "Less secure app access" OFF (gunakan App Password)
- Cek email "Security alert" dari Google
- Pastikan tidak ada CAPTCHA yang perlu diselesaikan

**5. Cek Log Error**
```bash
tail -100 storage/logs/laravel.log
```

### Email Masuk Spam

1. Tambahkan SPF record (jika punya domain)
2. Gunakan email yang sama untuk FROM dan SMTP
3. Minta user cek folder spam

### Link Verifikasi Expired

Link expired setelah 60 menit. User bisa klik "Kirim Ulang":
```php
// Ubah expiry time di config/auth.php
'verification' => [
    'expire' => 120, // 2 jam
],
```

---

## 🛡️ Contoh Penggunaan Middleware 'verified'

### Melindungi Route '/settings'

```php
// routes/web.php

// Cara 1: Individual route
Route::get('/settings', [SettingsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('settings');

// Cara 2: Group routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update']);
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
});

// Cara 3: Controller constructor
class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
}
```

### Perilaku Middleware

Jika user belum verifikasi email:
- Di-redirect ke `/email/verify`
- Melihat halaman notice
- Bisa klik "Kirim Ulang Email Verifikasi"

---

## 📝 Customisasi Template Email

### Edit Notification

```php
// app/Notifications/CustomVerifyEmail.php
public function toMail($notifiable)
{
    $verificationUrl = $this->verificationUrl($notifiable);

    return (new MailMessage)
        ->subject('Verifikasi Email - Sistem Kesiswaan')
        ->greeting('Halo ' . $notifiable->nama . '!')
        ->line('Terima kasih telah mendaftar.')
        ->action('Verifikasi Email', $verificationUrl)
        ->line('Link ini akan kadaluarsa dalam 60 menit.')
        ->salutation('Salam, Tim Kesiswaan');
}
```

### Publish Template Blade

```bash
php artisan vendor:publish --tag=laravel-mail
```

Edit di: `resources/views/vendor/mail/html/`

---

## 🔍 Monitoring

### Cek Status Verifikasi User

```php
// Di controller atau tinker
$user = User::find(1);

if ($user->hasVerifiedEmail()) {
    echo "Email sudah terverifikasi";
} else {
    echo "Email belum terverifikasi";
    echo "Verified at: " . $user->email_verified_at;
}
```

### Statistik Verifikasi

```php
// Total user terverifikasi
$verified = User::whereNotNull('email_verified_at')->count();

// Total user belum verifikasi
$unverified = User::whereNull('email_verified_at')->count();
```

---

## 📚 Referensi

- [Laravel Email Verification](https://laravel.com/docs/11.x/verification)
- [Laravel Mail](https://laravel.com/docs/11.x/mail)
- [Gmail App Passwords](https://support.google.com/accounts/answer/185833)
- [Mailtrap Documentation](https://mailtrap.io/docs)

---

## ✅ Checklist Setup

- [ ] Model User implements MustVerifyEmail
- [ ] Konfigurasi MAIL_* di .env
- [ ] Test email dengan `php artisan email:test`
- [ ] Route verifikasi sudah ada
- [ ] Middleware 'verified' diterapkan
- [ ] View verify-email.blade.php ada
- [ ] CustomVerifyEmail notification dibuat
- [ ] Clear cache: `php artisan config:clear`
- [ ] Test registrasi user baru
- [ ] Cek email masuk (atau log)
- [ ] Test klik link verifikasi
- [ ] Test akses route protected

---

**Dibuat untuk Sistem Kesiswaan**  
*Update terakhir: 2025*
