# ROLE BK (BIMBINGAN KONSELING) - IMPLEMENTASI LENGKAP ✅

## PERUBAHAN YANG DILAKUKAN

### 1. DATABASE - Migration Users Table
**File:** `database/migrations/0001_01_01_000000_create_users_table.php`
- Tambah 'bk' ke enum role
- Enum sekarang: `['admin', 'kesiswaan', 'guru', 'wali_kelas', 'siswa', 'ortu', 'bk']`

### 2. AUTHENTICATION - AuthController
**File:** `app/Http/Controllers/AuthController.php`

**Validasi Register (Admin):**
```php
'role' => 'required|in:admin,kesiswaan,guru,siswa,ortu,bk'
```

**Validasi Public Register:**
```php
'role' => 'required|in:kesiswaan,guru,siswa,ortu,bk'
```

**Redirect After Login:**
```php
case 'bk':
    return redirect()->intended(route('dashboard'))
        ->with('success', 'Selamat datang Guru BK!');
```

### 3. DASHBOARD - DashboardController
**File:** `app/Http/Controllers/DashboardController.php`

**Tambah Case BK:**
```php
case 'bk':
    return $this->bkDashboard();
```

**Method bkDashboard():**
- Statistik total sesi BK
- BK bulan ini
- Total siswa
- Siswa bermasalah (yang punya pelanggaran)
- List BK terbaru (10 data)
- Chart 3 bulan terakhir

### 4. VIEW - BK Dashboard
**File:** `resources/views/dashboard/bk.blade.php`
- 4 Small boxes (Total BK, BK Bulan Ini, Siswa Bermasalah, Total Siswa)
- Chart statistik 3 bulan terakhir
- Tabel sesi BK terbaru
- Link ke halaman BK lengkap

### 5. ROUTES - web.php
**File:** `routes/web.php`

**Tambah BK ke Middleware:**
```php
// Pelanggaran, Prestasi, BK
Route::middleware('role:admin,kesiswaan,guru,wali_kelas,bk')->group(function () {
    Route::resource('pelanggaran', PelanggaranController::class);
    Route::resource('prestasi', PrestasiController::class);
    Route::resource('bk', BimbinganKonselingController::class);
});

// Komunikasi Ortu
Route::middleware('role:kesiswaan,wali_kelas,bk,ortu')->prefix('komunikasi')...
```

### 6. SIDEBAR - layouts/app.blade.php
**File:** `resources/views/layouts/app.blade.php`

**Akses Menu:**
- Pelanggaran & Prestasi: `admin,kesiswaan,guru,wali_kelas,bk`
- Bimbingan Konseling: `admin,kesiswaan,wali_kelas,bk`
- Komunikasi Ortu: `kesiswaan,wali_kelas,bk,ortu`

### 7. REGISTER FORMS
**File:** `resources/views/auth/register.blade.php`
- Tambah option: `<option value="bk">Guru BK</option>`

**File:** `resources/views/landing.blade.php`
- Tambah button di modal register:
```html
<a href="{{ route('signup') }}" class="btn btn-outline-secondary btn-lg">
    <i class="fas fa-comments"></i> Daftar sebagai Guru BK
</a>
```

---

## HAK AKSES ROLE BK

### ✅ DAPAT AKSES:
1. **Dashboard BK** - Statistik dan data BK
2. **Bimbingan Konseling** - CRUD sesi BK (create, read, update, delete)
3. **Pelanggaran** - View dan create pelanggaran siswa
4. **Prestasi** - View dan create prestasi siswa
5. **Komunikasi Ortu** - Komunikasi dengan orang tua siswa
6. **Panggilan Ortu** - Panggil orang tua untuk konseling

### ❌ TIDAK DAPAT AKSES:
1. **Data Master** (Siswa, Kelas, Guru, Jenis Pelanggaran, dll) - Hanya admin & kesiswaan
2. **Verifikasi** - Hanya admin & kesiswaan
3. **Sanksi** - Hanya admin & kesiswaan
4. **Laporan PDF** - Hanya admin & kesiswaan
5. **User Management** - Hanya admin
6. **Role Management** - Hanya admin
7. **Backup System** - Hanya admin

---

## TESTING

### 1. Create User BK
```sql
INSERT INTO users (nama, email, password, role, status, created_at, updated_at)
VALUES ('Guru BK Test', 'bk@test.com', '$2y$12$...', 'bk', 'approved', NOW(), NOW());
```

### 2. Login sebagai BK
- Email: `bk@test.com`
- Password: `password`
- Redirect ke: `/dashboard` (BK Dashboard)

### 3. Test Akses Menu
- ✅ Dashboard → BK Dashboard muncul
- ✅ Pelanggaran → Bisa akses
- ✅ Prestasi → Bisa akses
- ✅ Bimbingan Konseling → Bisa akses
- ✅ Komunikasi Ortu → Bisa akses
- ❌ Data Master → Tidak bisa akses (403)
- ❌ Verifikasi → Tidak bisa akses (403)

---

## ALUR KERJA GURU BK

1. **Login** → Masuk ke Dashboard BK
2. **Lihat Statistik** → Total sesi BK, siswa bermasalah
3. **Input Sesi BK** → Menu Bimbingan Konseling → Create
4. **Lihat Pelanggaran** → Monitoring siswa bermasalah
5. **Komunikasi Ortu** → Hubungi orang tua siswa bermasalah
6. **Panggil Ortu** → Buat panggilan untuk konseling

---

## DATABASE ENUM VERIFICATION

```sql
SHOW COLUMNS FROM users WHERE Field = 'role';
```

**Result:**
```
Type: enum('admin','kesiswaan','guru','wali_kelas','siswa','ortu','bk')
```

✅ **ROLE BK SUDAH TERINTEGRASI PENUH KE SISTEM!**
