# 🚨 QUICK FIX: Tidak Bisa Submit Pelanggaran

## ⚡ Solusi Tercepat (5 Menit)

### **Opsi 1: Gunakan Script Otomatis** ⭐ RECOMMENDED

```bash
# Windows
fix_pelanggaran.bat

# Atau manual:
php artisan pelanggaran:fix email@anda.com
```

### **Opsi 2: Manual Fix**

```bash
# 1. Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# 2. Perbaiki user
php artisan tinker
```

```php
// Di tinker:
$user = User::where('email', 'EMAIL_ANDA')->first();

// Cek role
echo $user->role; // Harus: admin, kesiswaan, guru, wali_kelas, atau bk

// Jika role salah:
$user->role = 'guru';
$user->save();

// Cek koneksi guru
$guru = Guru::where('users_id', $user->id)->first();

// Jika tidak ada:
Guru::create([
    'users_id' => $user->id,
    'nip' => 'AUTO-' . $user->id,
    'nama_guru' => $user->nama,
    'bidang_studi' => 'Umum',
    'status' => 'aktif'
]);

exit
```

```bash
# 3. Clear browser cache
# Tekan Ctrl+Shift+Del di browser
# Hapus cache dan cookies
# Restart browser

# 4. Test
# Login dan coba submit pelanggaran
```

---

## 🔍 Diagnosa Masalah

```bash
# Jalankan diagnosa otomatis
php artisan tinker
include 'diagnosa_pelanggaran.php';
diagnosaPelanggaran('email@anda.com');
exit
```

---

## ❓ Masalah Umum

### **"403 Forbidden"**
→ Role user salah  
→ Fix: Ubah role ke `guru` atau `admin`

### **"Guru pencatat required"**
→ User tidak terhubung dengan data guru  
→ Fix: Buat data guru untuk user

### **Form tidak submit**
→ JavaScript error atau CSRF token expired  
→ Fix: Refresh halaman (Ctrl+F5)

### **Dropdown kosong**
→ Data master kosong  
→ Fix: `php artisan db:seed`

---

## 📖 Dokumentasi Lengkap

Lihat: **PERBAIKAN_SUBMIT_PELANGGARAN.md**

---

## 💡 Tips

1. ✅ Selalu clear cache setelah perubahan
2. ✅ Cek browser console (F12) untuk error
3. ✅ Pastikan role user benar
4. ✅ Pastikan user terhubung dengan data guru

---

**Dibuat:** 2026-01-06  
**Update:** Setiap ada masalah baru
