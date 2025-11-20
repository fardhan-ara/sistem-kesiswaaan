# Sistem Verifikasi Terintegrasi

## Overview

Sistem verifikasi terintegrasi untuk Pelanggaran dan Prestasi yang memungkinkan Admin dan Kesiswaan untuk memverifikasi atau menolak data yang diinput oleh Guru.

## Fitur Verifikasi

### 1. Status Verifikasi

**3 Status:**
- **Menunggu** (menunggu) - Data baru yang belum diverifikasi
- **Diverifikasi** (diverifikasi) - Data sudah diverifikasi, poin dihitung
- **Ditolak** (ditolak) - Data ditolak, poin tidak dihitung

### 2. Role yang Dapat Verifikasi

**Hanya Admin dan Kesiswaan** yang dapat:
- ✅ Memverifikasi pelanggaran
- ✅ Menolak pelanggaran
- ✅ Memverifikasi prestasi
- ✅ Menolak prestasi

### 3. Role yang Dapat Input

**Guru, Wali Kelas, Admin, Kesiswaan** dapat:
- ✅ Menginput pelanggaran (status: menunggu)
- ✅ Menginput prestasi (status: menunggu)
- ✅ Edit data
- ✅ Hapus data

## Implementasi

### Routes (routes/web.php)

```php
// Verifikasi Pelanggaran
Route::post('pelanggaran/{pelanggaran}/verify', [PelanggaranController::class, 'verify'])
    ->name('pelanggaran.verify')
    ->middleware('role:admin,kesiswaan');

Route::post('pelanggaran/{pelanggaran}/reject', [PelanggaranController::class, 'reject'])
    ->name('pelanggaran.reject')
    ->middleware('role:admin,kesiswaan');

// Verifikasi Prestasi
Route::post('prestasi/{prestasi}/verify', [PrestasiController::class, 'verify'])
    ->name('prestasi.verify')
    ->middleware('role:admin,kesiswaan');

Route::post('prestasi/{prestasi}/reject', [PrestasiController::class, 'reject'])
    ->name('prestasi.reject')
    ->middleware('role:admin,kesiswaan');
```

### Controller Methods

#### PelanggaranController

**verify():**
- Mengubah status menjadi 'diverifikasi'
- Menyimpan guru_verifikator
- Menyimpan tanggal_verifikasi
- Hanya admin dan kesiswaan

**reject():**
- Mengubah status menjadi 'ditolak'
- Menyimpan guru_verifikator
- Menyimpan tanggal_verifikasi
- Hanya admin dan kesiswaan

#### PrestasiController

**verify():**
- Mengubah status menjadi 'diverifikasi'
- Menyimpan guru_verifikator
- Menyimpan tanggal_verifikasi
- Hanya admin dan kesiswaan

**reject():**
- Mengubah status menjadi 'ditolak'
- Menyimpan guru_verifikator
- Menyimpan tanggal_verifikasi
- Hanya admin dan kesiswaan

### View (Index Pelanggaran & Prestasi)

**Tombol Verifikasi:**
- Hanya muncul untuk Admin dan Kesiswaan
- Hanya muncul jika status = 'menunggu'
- Tombol hijau (✓) untuk verifikasi
- Tombol merah (✗) untuk tolak

**Badge Status:**
- 🟡 Menunggu (badge-warning)
- 🟢 Diverifikasi (badge-success)
- 🔴 Ditolak (badge-danger)

## Workflow

### Pelanggaran

1. **Input** (Guru/Wali Kelas/Admin/Kesiswaan)
   - Guru menginput pelanggaran siswa
   - Status otomatis: 'menunggu'
   - Poin belum dihitung

2. **Verifikasi** (Admin/Kesiswaan)
   - Admin/Kesiswaan melihat daftar pelanggaran
   - Klik tombol ✓ untuk verifikasi
   - Status berubah: 'diverifikasi'
   - Poin dihitung untuk total siswa

3. **Tolak** (Admin/Kesiswaan)
   - Admin/Kesiswaan melihat daftar pelanggaran
   - Klik tombol ✗ untuk tolak
   - Status berubah: 'ditolak'
   - Poin tidak dihitung

### Prestasi

1. **Input** (Guru/Wali Kelas/Admin/Kesiswaan)
   - Guru menginput prestasi siswa
   - Status otomatis: 'menunggu'
   - Poin belum dihitung

2. **Verifikasi** (Admin/Kesiswaan)
   - Admin/Kesiswaan melihat daftar prestasi
   - Klik tombol ✓ untuk verifikasi
   - Status berubah: 'diverifikasi'
   - Poin dihitung untuk reward siswa

3. **Tolak** (Admin/Kesiswaan)
   - Admin/Kesiswaan melihat daftar prestasi
   - Klik tombol ✗ untuk tolak
   - Status berubah: 'ditolak'
   - Poin tidak dihitung

## Keamanan

### Authorization

✅ Middleware `role:admin,kesiswaan` pada route verifikasi
✅ Double check di controller method
✅ Abort 403 jika unauthorized

### Audit Trail

✅ Menyimpan siapa yang verifikasi (guru_verifikator)
✅ Menyimpan kapan diverifikasi (tanggal_verifikasi)
✅ Status history dapat dilacak

## Perhitungan Poin

### Pelanggaran

```php
// Hanya poin dengan status 'diverifikasi' yang dihitung
$totalPoin = Pelanggaran::where('siswa_id', $siswa->id)
    ->where('status_verifikasi', 'diverifikasi')
    ->sum('poin');
```

### Prestasi

```php
// Hanya poin dengan status 'diverifikasi' yang dihitung
$totalPoin = Prestasi::where('siswa_id', $siswa->id)
    ->where('status_verifikasi', 'diverifikasi')
    ->sum('poin');
```

## UI/UX

### Tampilan Index

**Kolom Status:**
- Badge berwarna sesuai status
- Mudah dibedakan secara visual

**Tombol Aksi:**
- Icon yang jelas (✓ dan ✗)
- Tooltip untuk informasi
- Hanya muncul jika relevan

**Keterangan:**
- Card info di bawah tabel
- Menjelaskan arti setiap status
- Membantu user memahami sistem

## Testing

### Test Verifikasi Pelanggaran

1. Login sebagai Guru
2. Input pelanggaran baru
3. Logout, login sebagai Kesiswaan
4. Buka menu Pelanggaran
5. Klik tombol ✓ pada pelanggaran
6. Status berubah menjadi 'Diverifikasi'

### Test Tolak Prestasi

1. Login sebagai Guru
2. Input prestasi baru
3. Logout, login sebagai Admin
4. Buka menu Prestasi
5. Klik tombol ✗ pada prestasi
6. Status berubah menjadi 'Ditolak'

## Sesuai dengan Modul

✅ **Modul 1**: Struktur tabel dengan status_verifikasi
✅ **Modul 2**: Workflow verifikasi berjenjang
✅ **Modul 2**: Role-based access control
✅ **Modul 2**: Audit trail dan tracking

## Manfaat

1. **Akurasi Data**: Data diverifikasi sebelum dihitung
2. **Kontrol Kualitas**: Admin/Kesiswaan dapat filter data
3. **Transparansi**: Semua perubahan tercatat
4. **Keamanan**: Hanya role tertentu yang bisa verifikasi
5. **Audit**: History verifikasi tersimpan
