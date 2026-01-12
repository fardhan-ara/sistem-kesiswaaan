# 🔐 Akun Login SIKAP

## Akun yang Tersedia

### 1. **Admin** (Full Access)
- **Email**: `admin@test.com`
- **Password**: `password`
- **Role**: Admin
- **Akses**: Semua fitur termasuk manage users dan backup system

### 2. **Verifikator / Staff Kesiswaan** (Verifikasi Data)
- **Email**: `kesiswaan@test.com`
- **Password**: `password`
- **Role**: Kesiswaan
- **Akses**: Data master, verifikasi pelanggaran/prestasi, sanksi, laporan

### 3. **Guru**
- **Email**: `guru@test.com`
- **Password**: `password`
- **Role**: Guru
- **Akses**: Input pelanggaran dan prestasi

### 4. **Wali Kelas**
- **Email**: `walikelas@test.com`
- **Password**: `password`
- **Role**: Wali Kelas
- **Akses**: Pelanggaran, prestasi, bimbingan konseling

### 5. **Siswa**
- **Email**: `siswa@test.com`
- **Password**: `password`
- **Role**: Siswa
- **Akses**: Lihat data pribadi saja

### 6. **Orang Tua**
- **Email**: `ortu@test.com`
- **Password**: `password`
- **Role**: Orang Tua
- **Akses**: Lihat data anak

## 🚀 Cara Login

1. Buka browser dan akses: `http://localhost:8000`
2. Klik tombol **Login** atau langsung ke `/login`
3. Masukkan email dan password sesuai role yang diinginkan
4. Klik **Masuk**
5. Anda akan diarahkan ke dashboard sesuai role

## 📊 Dashboard Berdasarkan Role

- **Admin & Kesiswaan**: Dashboard lengkap dengan statistik dan grafik
- **Guru & Wali Kelas**: Dashboard dengan data pelanggaran dan prestasi
- **Siswa**: Dashboard data pribadi
- **Orang Tua**: Dashboard data anak

## ⚠️ Catatan Keamanan

- **PENTING**: Ganti password default setelah login pertama kali
- Password default `password` hanya untuk testing
- Untuk production, gunakan password yang kuat

## 🔄 Reset Database (Jika Diperlukan)

Jika ingin reset semua data dan buat ulang akun:

```bash
php artisan migrate:fresh --seed
```

Perintah ini akan:
1. Drop semua tabel
2. Buat ulang struktur database
3. Isi dengan data dummy termasuk akun-akun di atas
