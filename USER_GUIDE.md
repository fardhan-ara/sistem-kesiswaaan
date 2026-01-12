# 📖 User Guide - Sistem Kesiswaan (SIKAP)

**Sistem Informasi Kesiswaan dan Prestasi**  
Version 1.0 | Last Updated: 2025-01-12

---

## 📑 Daftar Isi

1. [Pengenalan Sistem](#pengenalan-sistem)
2. [Login & Akses](#login--akses)
3. [Panduan Per Role](#panduan-per-role)
4. [Fitur Utama](#fitur-utama)
5. [FAQ](#faq)
6. [Troubleshooting](#troubleshooting)

---

## 🎯 Pengenalan Sistem

SIKAP adalah sistem informasi berbasis web untuk mengelola data kesiswaan, pelanggaran, prestasi, sanksi, dan bimbingan konseling.

### Fitur Utama:
- ✅ Manajemen data siswa, guru, dan kelas
- ✅ Pencatatan pelanggaran dengan poin otomatis
- ✅ Pencatatan prestasi siswa
- ✅ Sanksi otomatis (poin ≥ 100)
- ✅ Bimbingan konseling
- ✅ Komunikasi orang tua
- ✅ Laporan PDF
- ✅ Dashboard statistik

---

## 🔐 Login & Akses

### URL Akses
```
http://localhost:8000
atau
http://127.0.0.1:8000
```

### Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@test.com | password |

### Cara Login

1. Buka browser (Chrome/Firefox/Edge)
2. Akses URL sistem
3. Masukkan email dan password
4. Klik tombol "Login"
5. Sistem akan redirect ke dashboard sesuai role

---

## 👥 Panduan Per Role

### 1️⃣ ADMIN

**Akses Penuh Sistem**

#### Menu yang Tersedia:
- 📊 Dashboard
- 👨‍🎓 Data Master (Siswa, Guru, Kelas, Tahun Ajaran)
- 📋 Jenis Pelanggaran & Prestasi
- ⚠️ Pelanggaran & Prestasi
- ⚖️ Sanksi
- 💬 Bimbingan Konseling
- 📄 Laporan PDF
- 👥 Manage Users
- 🔧 Role Management
- 💾 Backup System

#### Tugas Utama:
1. **Kelola Data Master**
   - Tambah/Edit/Hapus Siswa
   - Tambah/Edit/Hapus Guru
   - Tambah/Edit/Hapus Kelas
   - Atur Tahun Ajaran

2. **Verifikasi Data**
   - Approve/Reject Pelanggaran
   - Approve/Reject Prestasi
   - Approve/Reject User Baru

3. **Kelola User**
   - Tambah user baru
   - Edit role user
   - Approve/Reject pendaftaran

4. **Generate Laporan**
   - Laporan Siswa (PDF)
   - Laporan Pelanggaran (PDF)
   - Laporan Prestasi (PDF)

#### Langkah-langkah:

**Menambah Siswa:**
```
1. Menu Siswa → Tambah Siswa
2. Isi form:
   - NIS
   - Nama Siswa
   - Kelas
   - Jenis Kelamin
   - Tahun Ajaran
3. Klik Simpan
```

**Verifikasi Pelanggaran:**
```
1. Menu Pelanggaran
2. Klik tombol "Verifikasi" pada data yang pending
3. Pilih: Setujui / Tolak / Revisi
4. Isi alasan jika tolak/revisi
5. Klik Simpan
```

---

### 2️⃣ KESISWAAN

**Akses Hampir Sama dengan Admin**

#### Menu yang Tersedia:
- 📊 Dashboard
- 👨‍🎓 Data Master
- ⚠️ Pelanggaran & Prestasi
- ⚖️ Sanksi
- 💬 Bimbingan Konseling
- 📄 Laporan PDF
- 👪 Approval Biodata Ortu

#### Tugas Utama:
1. Kelola data siswa
2. Verifikasi pelanggaran & prestasi
3. Kelola sanksi
4. Approve biodata orang tua
5. Generate laporan

---

### 3️⃣ GURU

**Input Pelanggaran & Prestasi**

#### Menu yang Tersedia:
- 📊 Dashboard
- ⚠️ Pelanggaran (Input)
- 🏆 Prestasi (Input)

#### Tugas Utama:

**Input Pelanggaran:**
```
1. Menu Pelanggaran → Tambah Pelanggaran
2. Pilih Siswa
3. Pilih Jenis Pelanggaran
4. Isi Keterangan
5. Klik Simpan
6. Status: Menunggu Verifikasi
```

**Input Prestasi:**
```
1. Menu Prestasi → Tambah Prestasi
2. Pilih Siswa
3. Pilih Jenis Prestasi
4. Isi Keterangan
5. Klik Simpan
6. Status: Menunggu Verifikasi
```

---

### 4️⃣ WALI KELAS

**Kelola Kelas & Input Data**

#### Menu yang Tersedia:
- 📊 Dashboard Kelas
- 👨‍🎓 Siswa Kelas
- ⚠️ Input Pelanggaran
- 🏆 Input Prestasi
- 💬 Komunikasi Ortu
- 📄 Laporan Kelas

#### Tugas Utama:

**Melihat Data Kelas:**
```
1. Menu Dashboard Kelas
2. Lihat statistik kelas
3. Lihat daftar siswa
```

**Komunikasi dengan Orang Tua:**
```
1. Menu Komunikasi Ortu
2. Klik "Kirim Pesan"
3. Pilih Siswa (otomatis ke ortu)
4. Pilih Jenis: Pesan / Pembinaan / Panggilan
5. Isi Pesan
6. Klik Kirim
```

---

### 5️⃣ BK (Bimbingan Konseling)

**Bimbingan & Konseling Siswa**

#### Menu yang Tersedia:
- 📊 Dashboard BK
- 💬 Bimbingan Konseling
- 👨‍🎓 Data Siswa
- 📧 Komunikasi Ortu

#### Tugas Utama:

**Input Sesi BK:**
```
1. Menu Bimbingan Konseling → Tambah
2. Pilih Siswa
3. Pilih Kategori (Akademik/Sosial/Pribadi/Karir)
4. Isi Permasalahan
5. Isi Solusi/Tindak Lanjut
6. Klik Simpan
```

---

### 6️⃣ SISWA

**Lihat Data Pribadi**

#### Menu yang Tersedia:
- 📊 Dashboard
- 👤 Profil

#### Yang Bisa Dilihat:
- Total pelanggaran
- Total prestasi
- Total poin pelanggaran
- Sanksi aktif
- Riwayat pelanggaran
- Riwayat prestasi

---

### 7️⃣ ORANG TUA

**Monitor Anak**

#### Menu yang Tersedia:
- 📊 Dashboard
- 📧 Pesan & Pembinaan
- 👤 Profil

#### Tugas Utama:

**Lengkapi Biodata (Pertama Kali):**
```
1. Login pertama kali
2. Akan muncul modal biodata
3. Isi data lengkap:
   - Nama Ayah/Ibu
   - NIK
   - Upload KTP
   - Upload KK
   - Alamat
   - No. Telp
4. Klik Simpan
5. Tunggu approval admin
```

**Lihat Data Anak:**
```
1. Dashboard menampilkan:
   - Total pelanggaran anak
   - Total prestasi anak
   - Sanksi aktif
   - Riwayat terbaru
```

**Balas Pesan dari Sekolah:**
```
1. Menu Pesan & Pembinaan
2. Klik pesan yang masuk
3. Klik "Balas"
4. Tulis balasan
5. Klik Kirim
```

---

## 🎨 Fitur Utama

### 1. Dashboard

**Untuk Admin/Kesiswaan:**
- Statistik total siswa, pelanggaran, prestasi
- Grafik pelanggaran per bulan
- Grafik prestasi per bulan
- Top 5 siswa bermasalah
- Kategori pelanggaran terbanyak

**Untuk Guru:**
- Total pelanggaran yang diinput
- Total prestasi yang diinput
- Data bulan ini
- Pelanggaran terbaru

**Untuk Siswa:**
- Total poin pelanggaran
- Total prestasi
- Sanksi aktif
- Riwayat terbaru

**Untuk Orang Tua:**
- Data lengkap anak
- Pelanggaran anak
- Prestasi anak
- Pesan dari sekolah

---

### 2. Manajemen Siswa

**Tambah Siswa:**
1. Klik "Tambah Siswa"
2. Isi form lengkap
3. Simpan

**Edit Siswa:**
1. Klik tombol "Edit" pada data siswa
2. Ubah data yang perlu
3. Simpan

**Hapus Siswa:**
1. Klik tombol "Hapus"
2. Konfirmasi
3. Data terhapus (beserta user-nya)

---

### 3. Pelanggaran

**Alur Pelanggaran:**
```
Input (Guru) → Menunggu Verifikasi → 
Verifikasi (Admin/Kesiswaan) → Disetujui/Ditolak →
Jika Poin ≥ 100 → Sanksi Otomatis
```

**Status Pelanggaran:**
- 🟡 Menunggu: Belum diverifikasi
- 🟢 Diverifikasi: Sudah disetujui
- 🔴 Ditolak: Ditolak dengan alasan

---

### 4. Prestasi

**Alur Prestasi:**
```
Input (Guru) → Menunggu Verifikasi → 
Verifikasi (Admin/Kesiswaan) → Disetujui/Ditolak
```

**Tingkat Prestasi:**
- Sekolah (poin rendah)
- Kota (poin sedang)
- Provinsi (poin tinggi)
- Nasional (poin sangat tinggi)
- Internasional (poin tertinggi)

---

### 5. Sanksi Otomatis

**Cara Kerja:**
1. Sistem menghitung total poin pelanggaran siswa
2. Jika poin ≥ 100 → Sanksi otomatis dibuat
3. Notifikasi email ke siswa & orang tua
4. Status sanksi: Direncanakan → Sedang Dilaksanakan → Selesai

---

### 6. Laporan PDF

**Generate Laporan:**
```
1. Menu Laporan
2. Pilih jenis laporan:
   - Laporan Siswa
   - Laporan Pelanggaran
   - Laporan Prestasi
3. Pilih filter:
   - Kelas
   - Tanggal Mulai
   - Tanggal Selesai
4. Klik "Export PDF"
5. File PDF akan terdownload
```

---

### 7. Komunikasi Orang Tua

**Jenis Komunikasi:**
- 📧 Pesan Biasa
- 📝 Pembinaan
- 📞 Panggilan Orang Tua

**Kirim Pesan:**
```
1. Menu Komunikasi
2. Klik "Kirim Pesan"
3. Pilih siswa
4. Pilih jenis
5. Tulis pesan
6. Kirim
```

**Status Pesan:**
- Terkirim
- Dibaca
- Dibalas

---

## ❓ FAQ (Frequently Asked Questions)

### Q: Lupa password, bagaimana?
**A:** Hubungi admin untuk reset password.

### Q: Data siswa tidak muncul?
**A:** Pastikan:
1. User sudah approved
2. Data siswa sudah terhubung dengan user
3. Refresh halaman (Ctrl+F5)

### Q: Pelanggaran tidak bisa diinput?
**A:** Cek:
1. Role Anda (harus Guru/Wali Kelas/Admin/Kesiswaan)
2. Data siswa ada
3. Jenis pelanggaran ada

### Q: Sanksi tidak otomatis muncul?
**A:** Sanksi otomatis muncul jika:
- Total poin pelanggaran ≥ 100
- Pelanggaran sudah diverifikasi

### Q: Orang tua tidak bisa login?
**A:** Cek:
1. Akun sudah approved admin
2. Biodata sudah approved admin
3. Email dan password benar

### Q: Laporan PDF kosong?
**A:** Pastikan:
1. Ada data di periode yang dipilih
2. Filter sudah benar
3. Browser support PDF

---

## 🔧 Troubleshooting

### Error: "Page Expired"
**Solusi:**
1. Refresh halaman (F5)
2. Login ulang
3. Clear browser cache

### Error: "Unauthorized"
**Solusi:**
1. Cek role Anda
2. Login ulang
3. Hubungi admin

### Data Tidak Muncul
**Solusi:**
1. Refresh halaman (Ctrl+F5)
2. Clear cache browser
3. Cek koneksi internet
4. Cek status approval data

### Upload File Gagal
**Solusi:**
1. Cek ukuran file (max 2MB)
2. Cek format file (JPG/PNG/PDF)
3. Coba file lain

### Email Tidak Terkirim
**Solusi:**
1. Cek konfigurasi email di .env
2. Cek koneksi internet
3. Cek spam folder

---

## 📞 Kontak Support

**Email:** kesiswaan@sman1.sch.id  
**Telp:** -  
**Jam Kerja:** Senin-Jumat, 07:00-15:00

---

## 📝 Tips & Trik

### Untuk Admin:
- ✅ Backup database secara berkala
- ✅ Verifikasi data setiap hari
- ✅ Monitor log sistem
- ✅ Update master data di awal tahun ajaran

### Untuk Guru:
- ✅ Input pelanggaran segera setelah kejadian
- ✅ Tulis keterangan yang jelas
- ✅ Dokumentasikan bukti jika perlu

### Untuk Wali Kelas:
- ✅ Monitor siswa kelas secara berkala
- ✅ Komunikasi rutin dengan orang tua
- ✅ Generate laporan kelas setiap bulan

### Untuk Orang Tua:
- ✅ Cek dashboard secara rutin
- ✅ Balas pesan dari sekolah
- ✅ Koordinasi dengan wali kelas

---

## 🔄 Update & Maintenance

**Sistem akan diupdate secara berkala untuk:**
- Perbaikan bug
- Penambahan fitur
- Peningkatan performa
- Keamanan sistem

**Jadwal Maintenance:**
- Setiap Minggu malam (22:00-24:00)
- Sistem mungkin tidak dapat diakses

---

## 📚 Dokumentasi Lengkap

Untuk dokumentasi teknis dan troubleshooting detail, lihat:
- `docs/INDEX.md` - Index dokumentasi
- `docs/TROUBLESHOOTING_*.md` - Panduan troubleshooting
- `SYSTEM_HEALTH_REPORT.md` - Status sistem

---

**© 2025 SIKAP - Sistem Informasi Kesiswaan dan Prestasi**  
*Version 1.0 | Developed with ❤️ for Education*
