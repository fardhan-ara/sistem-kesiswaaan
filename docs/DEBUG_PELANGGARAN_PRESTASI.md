# 🔍 Debug: Pelanggaran & Prestasi Redirect ke Dashboard

## 🎯 Langkah Debugging

### 1. Clear All Cache
```bash
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:clear
```

### 2. Test Route Debug
Akses: `http://127.0.0.1:8000/test-pelanggaran`

Jika muncul error, catat pesan errornya.

### 3. Cek Log Laravel
File: `storage/logs/laravel.log`

Cari error terbaru dengan keyword "Pelanggaran Index Error" atau "Prestasi Index Error"

### 4. Test Manual di Browser

**Login:**
```
Email: admin@test.com
Password: password
```

**Test Pelanggaran:**
1. Klik menu "Pelanggaran"
2. Jika redirect ke dashboard, cek pesan error di dashboard
3. Buka Developer Tools (F12) → Console
4. Cek apakah ada error JavaScript

**Test Prestasi:**
1. Klik menu "Prestasi"
2. Jika redirect ke dashboard, cek pesan error di dashboard
3. Buka Developer Tools (F12) → Console
4. Cek apakah ada error JavaScript

---

## 🐛 Kemungkinan Penyebab

### 1. View File Tidak Ditemukan
**Cek:**
```bash
dir resources\views\pelanggaran\index.blade.php
dir resources\views\prestasi\index.blade.php
```

**Solusi:** File harus ada

### 2. Relasi Model Error
**Cek di tinker:**
```bash
php artisan tinker
>>> $p = App\Models\Pelanggaran::with(['siswa', 'jenisPelanggaran'])->first();
>>> $p->siswa->nama_siswa
>>> $p->jenisPelanggaran->nama_pelanggaran
```

**Solusi:** Pastikan relasi benar

### 3. Data Kosong
**Cek:**
```bash
php artisan tinker
>>> DB::table('pelanggarans')->count()
>>> DB::table('prestasis')->count()
```

**Solusi:** Jika 0, buat data dummy

### 4. Middleware Block
**Cek role user:**
```bash
php artisan tinker
>>> $user = App\Models\User::where('email', 'admin@test.com')->first();
>>> $user->role
>>> $user->status
```

**Solusi:** Role harus: admin, kesiswaan, guru, atau wali_kelas

---

## ✅ Perbaikan yang Sudah Dilakukan

1. ✅ Tambah try-catch di PelanggaranController::index()
2. ✅ Tambah try-catch di PrestasiController::index()
3. ✅ Tambah error logging
4. ✅ Tambah debug route /test-pelanggaran
5. ✅ Clear all cache

---

## 🔧 Solusi Alternatif

### Jika Masih Redirect:

**1. Cek Handler Exception**
File: `app/Exceptions/Handler.php`

Pastikan tidak ada custom redirect ke dashboard

**2. Cek Middleware**
File: `app/Http/Middleware/RoleMiddleware.php`

Pastikan tidak ada redirect ke dashboard

**3. Recreate View**
```bash
# Backup dulu
copy resources\views\pelanggaran\index.blade.php resources\views\pelanggaran\index.blade.php.bak

# Buat ulang dengan konten minimal
```

**4. Test dengan Route Sederhana**
Tambah di `routes/web.php`:
```php
Route::get('/test-simple', function() {
    return 'Route OK';
})->middleware('auth');
```

Jika ini bisa diakses, berarti masalah di controller/view.

---

## 📝 Checklist Debugging

- [ ] Clear cache berhasil
- [ ] Route /test-pelanggaran bisa diakses
- [ ] File view ada
- [ ] Data di database ada
- [ ] User role benar
- [ ] Log error dicek
- [ ] Browser console dicek
- [ ] Middleware tidak block

---

## 🚀 Jika Sudah Fix

1. Hapus route debug `/test-pelanggaran`
2. Test akses normal via menu
3. Test CRUD lengkap
4. Update dokumentasi

---

**Status**: 🔍 DEBUGGING MODE
**Next**: Cek error message di dashboard atau log
