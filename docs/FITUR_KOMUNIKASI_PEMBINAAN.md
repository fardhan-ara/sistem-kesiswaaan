# Fitur Komunikasi & Pembinaan Orang Tua

## 🎯 Tujuan
Memfasilitasi komunikasi 2 arah antara sekolah (guru/kesiswaan) dengan orang tua untuk pembinaan siswa yang efektif.

## ✨ Fitur Utama

### 1. **Pesan & Komunikasi**
- Kirim pesan antara guru/kesiswaan dengan orang tua
- 3 Jenis pesan:
  - **Pesan Biasa**: Komunikasi umum
  - **Laporan Pembinaan**: Laporan perkembangan siswa
  - **Konsultasi**: Permintaan konsultasi
- Sistem balasan (reply) dengan thread
- Upload lampiran (max 5MB)
- Status: Terkirim → Dibaca → Dibalas
- Notifikasi pesan belum dibaca

### 2. **Panggilan Orang Tua**
- Guru/kesiswaan buat panggilan ortu
- Terkait pelanggaran atau pembinaan umum
- Informasi lengkap:
  - Judul & keterangan
  - Tanggal & waktu
  - Tempat pertemuan
- Orang tua konfirmasi kehadiran
- Catatan hasil pertemuan
- Status: Menunggu → Dikonfirmasi → Selesai

### 3. **Riwayat Komunikasi**
- Track semua interaksi
- Filter pesan belum dibaca
- History lengkap per siswa

## 📊 Database

### Tabel `komunikasi_ortus`
```sql
- siswa_id (FK)
- pengirim_id (FK users)
- penerima_id (FK users)
- jenis (pesan/laporan_pembinaan/konsultasi)
- subjek
- isi_pesan
- lampiran
- status (terkirim/dibaca/dibalas)
- dibaca_at
```

### Tabel `balasan_komunikasis`
```sql
- komunikasi_id (FK)
- pengirim_id (FK users)
- isi_balasan
- lampiran
```

### Tabel `panggilan_ortus`
```sql
- siswa_id (FK)
- pelanggaran_id (FK, nullable)
- dibuat_oleh (FK users)
- judul
- keterangan
- tanggal_panggilan
- tempat
- status (menunggu_konfirmasi/dikonfirmasi/selesai/dibatalkan)
- catatan_hasil
- dikonfirmasi_at
```

## 🔄 Alur Penggunaan

### Untuk Guru/Kesiswaan:
1. **Kirim Pesan**:
   - Menu "Pesan & Pembinaan" → "Kirim Pesan"
   - Pilih siswa → Otomatis dapat orang tuanya
   - Pilih jenis pesan
   - Tulis pesan + lampiran (opsional)
   - Kirim

2. **Buat Panggilan Ortu**:
   - Menu "Panggilan Ortu" → "Buat Panggilan"
   - Pilih siswa
   - Pilih pelanggaran terkait (opsional)
   - Isi detail panggilan
   - Kirim → Ortu dapat notifikasi

3. **Selesaikan Panggilan**:
   - Setelah ortu konfirmasi & pertemuan selesai
   - Klik "Selesaikan"
   - Isi catatan hasil pertemuan
   - Simpan

### Untuk Orang Tua:
1. **Baca Pesan**:
   - Menu "Pesan & Pembinaan"
   - Tab "Belum Dibaca" untuk pesan baru
   - Klik "Lihat" untuk baca detail
   - Balas pesan

2. **Konfirmasi Panggilan**:
   - Menu "Panggilan Ortu"
   - Lihat detail panggilan
   - Klik "Konfirmasi Kehadiran"
   - Datang sesuai jadwal

3. **Kirim Pesan ke Sekolah**:
   - Menu "Pesan & Pembinaan" → "Kirim Pesan"
   - Pilih siswa (anak)
   - Pilih penerima (guru/kesiswaan)
   - Tulis pesan
   - Kirim

## 🎨 Fitur Detail

### Keamanan:
- ✅ Hanya ortu yang terdaftar bisa akses
- ✅ Ortu hanya lihat data anaknya
- ✅ Guru/kesiswaan akses semua
- ✅ Validasi file upload

### User Experience:
- ✅ Badge notifikasi pesan belum dibaca
- ✅ Status real-time (terkirim/dibaca/dibalas)
- ✅ Thread balasan terorganisir
- ✅ Download lampiran
- ✅ Responsive design

### Integrasi:
- ✅ Terintegrasi dengan data siswa
- ✅ Link ke pelanggaran
- ✅ Relasi dengan biodata ortu
- ✅ API helper untuk dropdown dinamis

## 📱 Menu Sidebar

**Untuk Semua Role:**
- "Pesan & Pembinaan" → Inbox & kirim pesan

**Untuk Admin/Kesiswaan/Guru/Ortu:**
- "Panggilan Ortu" → Daftar panggilan

## 🚀 Cara Menggunakan

### Test Fitur:
1. **Login sebagai Guru/Kesiswaan**
2. Klik menu "Pesan & Pembinaan"
3. Klik "Kirim Pesan"
4. Pilih siswa yang orang tuanya sudah terdaftar
5. Isi form dan kirim
6. **Login sebagai Orang Tua**
7. Lihat pesan masuk
8. Balas pesan

### Test Panggilan:
1. **Login sebagai Guru/Kesiswaan**
2. Menu "Panggilan Ortu" → "Buat Panggilan"
3. Isi form panggilan
4. **Login sebagai Orang Tua**
5. Menu "Panggilan Ortu"
6. Klik "Konfirmasi Kehadiran"
7. **Login sebagai Guru**
8. Setelah pertemuan, klik "Selesaikan"
9. Isi catatan hasil

## 💡 Manfaat

### Untuk Sekolah:
- ✅ Komunikasi terstruktur & terdokumentasi
- ✅ Track keterlibatan orang tua
- ✅ Laporan pembinaan tersimpan
- ✅ Efisiensi koordinasi

### Untuk Orang Tua:
- ✅ Update perkembangan anak real-time
- ✅ Komunikasi mudah dengan sekolah
- ✅ Konfirmasi panggilan online
- ✅ Riwayat komunikasi tersimpan

## 📝 Catatan

- File lampiran disimpan di `storage/app/public/komunikasi/`
- Pastikan storage link sudah dibuat: `php artisan storage:link`
- Orang tua harus sudah isi biodata & approved untuk akses fitur ini
- Semua komunikasi tercatat untuk audit trail

## ✅ Status
- ✅ Migration done
- ✅ Models created
- ✅ Controller ready
- ✅ Views created
- ✅ Routes registered
- ✅ Menu added
- ✅ API helpers ready

**FITUR SIAP DIGUNAKAN!**
