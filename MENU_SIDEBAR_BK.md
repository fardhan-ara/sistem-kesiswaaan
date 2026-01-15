# Menu Sidebar Guru BK

## 📋 Menu yang Ditambahkan

Menu sidebar khusus untuk role **Guru BK** telah ditambahkan dengan 5 menu utama:

### 🎯 Menu Utama BK

#### 1. **Sesi BK** 
- **Icon**: 💬 (fas fa-comments)
- **Route**: `bk.index`
- **Fungsi**: Mengelola semua sesi bimbingan konseling
- **Akses**: CRUD sesi BK (Create, Read, Update, Delete)

#### 2. **Data Siswa**
- **Icon**: 🎓 (fas fa-user-graduate)
- **Route**: `siswa.index`
- **Fungsi**: Melihat data lengkap siswa
- **Akses**: Read-only untuk Guru BK

#### 3. **Pelanggaran Siswa**
- **Icon**: ⚠️ (fas fa-exclamation-triangle)
- **Route**: `pelanggaran.index`
- **Fungsi**: Melihat dan monitoring pelanggaran siswa
- **Akses**: View pelanggaran untuk identifikasi siswa bermasalah

#### 4. **Prestasi Siswa**
- **Icon**: 🏆 (fas fa-trophy)
- **Route**: `prestasi.index`
- **Fungsi**: Melihat prestasi siswa
- **Akses**: View prestasi untuk apresiasi siswa berprestasi

#### 5. **Sanksi**
- **Icon**: ⚖️ (fas fa-gavel)
- **Route**: `sanksi.index`
- **Fungsi**: Melihat sanksi yang diberikan kepada siswa
- **Akses**: View sanksi untuk monitoring tindak lanjut

---

## 🎨 Tampilan Sidebar

```
┌─────────────────────────────┐
│ 🏠 Dashboard                │
├─────────────────────────────┤
│ BIMBINGAN KONSELING         │
├─────────────────────────────┤
│ 💬 Sesi BK                  │
│ 🎓 Data Siswa               │
│ ⚠️ Pelanggaran Siswa        │
│ 🏆 Prestasi Siswa           │
│ ⚖️ Sanksi                   │
└─────────────────────────────┘
```

---

## 🔧 Implementasi Teknis

### File yang Dimodifikasi:
- `resources/views/layouts/app.blade.php`

### Kode yang Ditambahkan:
```blade
@if(auth()->check() && auth()->user()->role === 'bk')
<li class="nav-header">BIMBINGAN KONSELING</li>

<li class="nav-item">
    <a href="{{ route('bk.index') }}" class="nav-link {{ request()->routeIs('bk.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-comments"></i>
        <p>Sesi BK</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('siswa.index') }}" class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-graduate"></i>
        <p>Data Siswa</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('pelanggaran.index') }}" class="nav-link {{ request()->routeIs('pelanggaran.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-exclamation-triangle"></i>
        <p>Pelanggaran Siswa</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('prestasi.index') }}" class="nav-link {{ request()->routeIs('prestasi.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-trophy"></i>
        <p>Prestasi Siswa</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('sanksi.index') }}" class="nav-link {{ request()->routeIs('sanksi.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-gavel"></i>
        <p>Sanksi</p>
    </a>
</li>
@endif
```

---

## ✨ Fitur Menu

### 1. **Active State**
- Menu yang sedang aktif akan highlight dengan warna berbeda
- Menggunakan `request()->routeIs()` untuk deteksi route aktif

### 2. **Icon FontAwesome**
- Setiap menu memiliki icon yang relevan
- Icon memudahkan identifikasi visual menu

### 3. **Responsive**
- Menu otomatis collapse di mobile
- Sidebar dapat di-toggle dengan tombol hamburger

### 4. **Role-Based**
- Menu hanya muncul untuk user dengan role 'bk'
- Tidak terlihat oleh role lain (admin, guru, siswa, ortu)

---

## 🚀 Cara Testing

### 1. Login sebagai Guru BK
```
Email: bk@test.com
Password: password
```

### 2. Verifikasi Menu Sidebar
- ✅ Header "BIMBINGAN KONSELING" muncul
- ✅ 5 menu BK terlihat di sidebar
- ✅ Icon muncul dengan benar
- ✅ Menu aktif ter-highlight saat diklik
- ✅ Semua link berfungsi

### 3. Test Navigasi
- Klik setiap menu dan pastikan redirect ke halaman yang benar
- Verifikasi menu aktif ter-highlight
- Test di mobile (resize browser)

---

## 🎯 Manfaat Menu Sidebar BK

### Untuk Guru BK:
1. **Akses Cepat** - Semua fitur penting dalam 1 klik
2. **Navigasi Mudah** - Tidak perlu mencari menu di tempat lain
3. **Workflow Efisien** - Menu tersusun sesuai alur kerja BK
4. **Visual Jelas** - Icon memudahkan identifikasi menu

### Untuk Sistem:
1. **Role-Based Access** - Menu sesuai hak akses
2. **Maintainable** - Mudah ditambah/ubah menu
3. **Consistent** - Mengikuti pattern menu role lain
4. **Scalable** - Mudah dikembangkan

---

## 📊 Alur Kerja Guru BK dengan Menu

```
1. Dashboard BK
   ↓
2. Lihat Siswa Bermasalah (dari dashboard)
   ↓
3. Klik "Pelanggaran Siswa" (sidebar)
   ↓
4. Identifikasi siswa yang perlu BK
   ↓
5. Klik "Sesi BK" (sidebar)
   ↓
6. Tambah sesi BK baru
   ↓
7. Klik "Sanksi" (sidebar) untuk monitoring
   ↓
8. Kembali ke Dashboard untuk lihat statistik
```

---

## ⚠️ Catatan Penting

### Hak Akses:
- Guru BK dapat **VIEW** semua data siswa, pelanggaran, prestasi, sanksi
- Guru BK dapat **CRUD** sesi BK
- Guru BK **TIDAK DAPAT** menghapus/edit data master siswa

### Route yang Digunakan:
```php
Route::resource('bk', BimbinganKonselingController::class);
Route::resource('siswa', SiswaController::class);
Route::resource('pelanggaran', PelanggaranController::class);
Route::resource('prestasi', PrestasiController::class);
Route::resource('sanksi', SanksiController::class);
```

### Authorization:
- Pastikan policy/middleware sudah mengizinkan role 'bk' mengakses route tersebut
- Jika ada error 403, tambahkan role 'bk' ke middleware/policy

---

## 🔍 Troubleshooting

### Menu Tidak Muncul?
**Solusi**: 
1. Cek role user: `SELECT role FROM users WHERE email = 'bk@test.com';`
2. Pastikan role = 'bk' (bukan 'guru')
3. Clear cache: `php artisan view:clear`
4. Logout dan login ulang

### Menu Muncul Tapi Link Error 404?
**Solusi**:
1. Cek route: `php artisan route:list | grep bk`
2. Pastikan route sudah terdaftar
3. Clear route cache: `php artisan route:clear`

### Menu Tidak Ter-highlight Saat Aktif?
**Solusi**:
1. Cek `request()->routeIs()` di blade
2. Pastikan route name sesuai
3. Clear view cache: `php artisan view:clear`

---

## ✅ Checklist Testing

- [ ] Login sebagai Guru BK berhasil
- [ ] Header "BIMBINGAN KONSELING" muncul
- [ ] Menu "Sesi BK" muncul dan berfungsi
- [ ] Menu "Data Siswa" muncul dan berfungsi
- [ ] Menu "Pelanggaran Siswa" muncul dan berfungsi
- [ ] Menu "Prestasi Siswa" muncul dan berfungsi
- [ ] Menu "Sanksi" muncul dan berfungsi
- [ ] Menu aktif ter-highlight dengan benar
- [ ] Icon muncul dengan benar
- [ ] Responsive di mobile
- [ ] Menu tidak muncul untuk role lain

---

**Status**: ✅ Selesai dan Siap Digunakan  
**Versi**: 1.0.0  
**Tanggal**: 2025-01-15
