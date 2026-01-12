# Setup Email Verifikasi - Panduan Praktis

## 🚀 Quick Start

### Opsi 1: Testing dengan Log (Paling Mudah)

Email akan tersimpan di file log, tidak perlu konfigurasi SMTP.

**1. Update .env:**
```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS="kesiswaan@sman1.sch.id"
MAIL_FROM_NAME="Sistem Kesiswaan"
```

**2. Clear cache:**
```bash
php artisan config:clear
php artisan cache:clear
```

**3. Test register user baru**

**4. Cek email di log:**
```bash
# Windows
type storage\logs\laravel.log | findstr "verify"

# Atau buka file: storage/logs/laravel.log
# Cari URL seperti: http://127.0.0.1:8000/email/verify/1/hash...
```

**5. Copy URL dan paste di browser untuk verifikasi**

---

### Opsi 2: Testing dengan Mailtrap (Recommended untuk Development)

Mailtrap adalah fake SMTP server untuk testing email.

**1. Daftar di [Mailtrap.io](https://mailtrap.io) (Gratis)**

**2. Buat inbox baru dan copy credentials**

**3. Update .env:**
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="kesiswaan@sman1.sch.id"
MAIL_FROM_NAME="Sistem Kesiswaan"
```

**4. Clear cache:**
```bash
php artisan config:clear
```

**5. Test register user baru**

**6. Cek email di Mailtrap inbox** (akan muncul dalam beberapa detik)

---

### Opsi 3: Production dengan Gmail

**1. Enable 2-Factor Authentication di Gmail**

**2. Generate App Password:**
   - Buka: https://myaccount.google.com/apppasswords
   - Pilih "Mail" dan "Other (Custom name)"
   - Copy password yang dihasilkan

**3. Update .env:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-digit-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="Sistem Kesiswaan"
```

**4. Clear cache:**
```bash
php artisan config:clear
```

---

## 🧪 Testing Email

### Test Kirim Email Manual

```bash
php artisan tinker
```

```php
// Di tinker console
Mail::raw('Test email dari Sistem Kesiswaan', function($msg) {
    $msg->to('test@example.com')
        ->subject('Test Email');
});

// Jika berhasil, akan return null
// Jika error, akan muncul pesan error
```

### Test Verifikasi Email untuk User Tertentu

```bash
php artisan tinker
```

```php
// Kirim email verifikasi ke user ID 1
$user = App\Models\User::find(1);
$user->sendEmailVerificationNotification();
```

### Verifikasi Manual User (Bypass Email)

```bash
php artisan tinker
```

```php
// Langsung verifikasi user tanpa email
$user = App\Models\User::find(1);
$user->markEmailAsVerified();
```

---

## 🔍 Troubleshooting

### Problem: Email tidak terkirim

**Solusi:**
```bash
# 1. Clear semua cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 2. Restart server
# Ctrl+C untuk stop
php artisan serve

# 3. Cek log error
tail -f storage/logs/laravel.log
```

### Problem: "Connection refused" error

**Penyebab:** SMTP credentials salah atau firewall blocking

**Solusi:**
1. Cek username/password SMTP
2. Cek port (587 untuk TLS, 465 untuk SSL)
3. Gunakan Mailtrap untuk testing dulu

### Problem: User sudah register tapi belum dapat email

**Solusi:**
```bash
php artisan tinker
```
```php
// Cari user berdasarkan email
$user = App\Models\User::where('email', 'user@example.com')->first();

// Kirim ulang email verifikasi
$user->sendEmailVerificationNotification();
```

### Problem: Link verifikasi expired

**Solusi:**
```php
// config/auth.php
'verification' => [
    'expire' => 60, // Ubah dari 60 menit ke 1440 (24 jam)
],
```

---

## 📋 Checklist Setup

- [ ] Update .env dengan konfigurasi email
- [ ] Run `php artisan config:clear`
- [ ] Test kirim email dengan tinker
- [ ] Register user baru
- [ ] Cek email terkirim (log/mailtrap/gmail)
- [ ] Klik link verifikasi
- [ ] Verifikasi berhasil (email_verified_at terisi)
- [ ] Test akses route dengan middleware 'verified'

---

## 🎯 Contoh Penggunaan Middleware 'verified'

### Melindungi Single Route

```php
// routes/web.php
Route::get('/settings', function () {
    return view('settings.index');
})->middleware(['auth', 'verified'])->name('settings');
```

**Penjelasan:**
- `auth` = User harus login
- `verified` = Email harus sudah diverifikasi
- Jika belum verifikasi → redirect ke `/email/verify`

### Melindungi Group Routes

```php
// routes/web.php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('siswa', SiswaController::class);
    Route::resource('pelanggaran', PelanggaranController::class);
    Route::get('/profile', [ProfileController::class, 'show']);
});
```

### Melindungi Route Tertentu Saja

```php
// Semua user bisa akses dashboard (tanpa verifikasi)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

// Hanya user terverifikasi bisa akses settings
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::get('/profile/edit', [ProfileController::class, 'edit']);
});
```

### Custom Redirect untuk Unverified User

```php
// app/Http/Middleware/EnsureEmailIsVerified.php (custom)
protected function redirectTo($request)
{
    if (! $request->expectsJson()) {
        return route('verification.notice');
    }
}
```

---

## 💡 Tips

1. **Development:** Gunakan `MAIL_MAILER=log` untuk cepat
2. **Testing:** Gunakan Mailtrap untuk test email real
3. **Production:** Gunakan Gmail atau AWS SES
4. **Queue:** Untuk production, gunakan queue agar tidak blocking:
   ```php
   // User.php
   public function sendEmailVerificationNotification()
   {
       $this->notify((new CustomVerifyEmail)->delay(now()->addSeconds(5)));
   }
   ```

---

## 📞 Support

Jika masih ada masalah:
1. Cek `storage/logs/laravel.log`
2. Test dengan `php artisan tinker`
3. Pastikan firewall tidak blocking port SMTP
4. Gunakan Mailtrap untuk isolasi masalah
