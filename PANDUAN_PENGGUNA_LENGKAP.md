# 📚 PANDUAN PENGGUNA LENGKAP - SISTEM KESISWAAN (SIKAP)

**Sistem Informasi Kesiswaan dan Prestasi**  
*Panduan Lengkap untuk Semua Pengguna*

---

## 📑 DAFTAR ISI

1. [Pengenalan Sistem](#1-pengenalan-sistem)
2. [Cara Mengakses Sistem](#2-cara-mengakses-sistem)
3. [Panduan Login](#3-panduan-login)
4. [Dashboard Utama](#4-dashboard-utama)
5. [Panduan untuk Administrator](#5-panduan-untuk-administrator)
6. [Panduan untuk Staff Kesiswaan](#6-panduan-untuk-staff-kesiswaan)
7. [Panduan untuk Guru](#7-panduan-untuk-guru)
8. [Panduan untuk Wali Kelas](#8-panduan-untuk-wali-kelas)
9. [Panduan untuk Guru BK](#9-panduan-untuk-guru-bk)
10. [Panduan untuk Siswa](#10-panduan-untuk-siswa)
11. [Panduan untuk Orang Tua](#11-panduan-untuk-orang-tua)
12. [Fitur Laporan](#12-fitur-laporan)
13. [Troubleshooting](#13-troubleshooting)
14. [FAQ](#14-faq)

---

## 1. PENGENALAN SISTEM

### 1.1 Apa itu SIKAP?

SIKAP (Sistem Informasi Kesiswaan dan Prestasi) adalah aplikasi berbasis web yang dirancang khusus untuk mengelola seluruh aspek kesiswaan di sekolah. Sistem ini membantu sekolah dalam:

- **Mengelola data siswa** secara terpusat dan terorganisir
- **Mencatat pelanggaran** dengan sistem poin otomatis
- **Mencatat prestasi** siswa dengan berbagai tingkatan
- **Mengelola sanksi** yang diberikan secara otomatis
- **Melakukan bimbingan konseling** dengan dokumentasi lengkap
- **Berkomunikasi dengan orang tua** secara real-time
- **Membuat laporan** dalam format PDF

### 1.2 Keunggulan Sistem

✅ **Otomatisasi Sanksi**: Sanksi dibuat otomatis ketika poin pelanggaran mencapai 100  
✅ **Verifikasi Berlapis**: Setiap data melalui proses verifikasi untuk akurasi  
✅ **Komunikasi Real-time**: Orang tua dapat memantau anak secara langsung  
✅ **Laporan Lengkap**: Export laporan dalam format PDF dengan filter  
✅ **Multi-Role**: Mendukung 7 jenis pengguna dengan hak akses berbeda  
✅ **Responsive**: Dapat diakses dari komputer, tablet, dan smartphone  

### 1.3 Jenis Pengguna

| No | Role | Deskripsi | Hak Akses |
|----|------|-----------|-----------|
| 1 | **Administrator** | Pengelola utama sistem | Akses penuh semua fitur |
| 2 | **Staff Kesiswaan** | Pengelola data kesiswaan | Hampir sama dengan admin |
| 3 | **Guru** | Pengajar di sekolah | Input pelanggaran & prestasi |
| 4 | **Wali Kelas** | Guru yang mengelola kelas | Kelola kelas + komunikasi ortu |
| 5 | **Guru BK** | Guru Bimbingan Konseling | Bimbingan konseling siswa |
| 6 | **Siswa** | Peserta didik | Lihat data pribadi |
| 7 | **Orang Tua** | Wali siswa | Monitor data anak |

---

## 2. CARA MENGAKSES SISTEM

### 2.1 Persyaratan Sistem

**Perangkat yang Didukung:**
- 💻 Komputer/Laptop (Windows, Mac, Linux)
- 📱 Tablet (Android, iOS)
- 📱 Smartphone (Android, iOS)

**Browser yang Didukung:**
- Google Chrome (Direkomendasikan)
- Mozilla Firefox
- Microsoft Edge
- Safari (untuk Mac/iOS)

**Koneksi Internet:**
- Minimal 1 Mbps untuk penggunaan normal
- 2 Mbps atau lebih untuk upload file

### 2.2 URL Akses

```
Lokal (Development): http://localhost:8000
Atau: http://127.0.0.1:8000

Production: [URL akan diberikan oleh admin]
```

### 2.3 Akun Default (untuk Testing)

| Role | Email | Password |
|------|-------|----------|
| Administrator | admin@test.com | password |
| Staff Kesiswaan | kesiswaan@test.com | password |
| Guru | guru@test.com | password |
| Siswa | siswa@test.com | password |

⚠️ **Penting**: Ganti password default setelah login pertama!

---

## 3. PANDUAN LOGIN

### 3.1 Langkah-langkah Login

1. **Buka Browser**
   - Pastikan koneksi internet stabil
   - Gunakan browser yang didukung

2. **Akses URL Sistem**
   - Ketik URL di address bar
   - Tekan Enter

3. **Masukkan Kredensial**
   - Email: Masukkan email yang terdaftar
   - Password: Masukkan password yang benar
   - Centang "Remember Me" jika diperlukan

4. **Klik Tombol Login**
   - Sistem akan memverifikasi data
   - Jika berhasil, akan diarahkan ke dashboard

### 3.2 Lupa Password

Jika lupa password:
1. Hubungi administrator sekolah
2. Berikan email yang terdaftar
3. Admin akan mereset password
4. Anda akan mendapat password baru
5. Login dan ganti password segera

### 3.3 Masalah Login

**Email/Password Salah:**
- Periksa kembali email dan password
- Pastikan Caps Lock tidak aktif
- Coba reset password

**Akun Belum Diverifikasi:**
- Hubungi administrator
- Tunggu proses approval
- Cek email untuk verifikasi

---

## 4. DASHBOARD UTAMA

### 4.1 Komponen Dashboard

Dashboard adalah halaman utama setelah login yang menampilkan:

**Header:**
- Logo sekolah
- Nama pengguna
- Menu logout
- Notifikasi

**Sidebar:**
- Menu navigasi sesuai role
- Collapse/expand menu
- Indikator menu aktif

**Content Area:**
- Statistik utama
- Grafik dan chart
- Tabel data terbaru
- Quick actions

**Footer:**
- Informasi sistem
- Copyright
- Link bantuan

### 4.2 Statistik Dashboard

**Untuk Admin/Kesiswaan:**
- 📊 Total siswa aktif
- ⚠️ Total pelanggaran bulan ini
- 🏆 Total prestasi bulan ini
- ⚖️ Sanksi aktif
- 📈 Grafik trend pelanggaran
- 📈 Grafik trend prestasi

**Untuk Guru:**
- 📝 Pelanggaran yang diinput
- 🏆 Prestasi yang diinput
- ⏳ Data pending verifikasi
- 📅 Aktivitas bulan ini

**Untuk Siswa:**
- ⚠️ Total poin pelanggaran
- 🏆 Total prestasi
- ⚖️ Sanksi aktif (jika ada)
- 📋 Riwayat terbaru

**Untuk Orang Tua:**
- 👨‍🎓 Data lengkap anak
- ⚠️ Pelanggaran anak
- 🏆 Prestasi anak
- 💬 Pesan dari sekolah

---

## 5. PANDUAN UNTUK ADMINISTRATOR

### 5.1 Menu yang Tersedia

Administrator memiliki akses penuh ke semua fitur:

**📊 Dashboard**
- Statistik lengkap sistem
- Grafik pelanggaran & prestasi
- Monitor aktivitas pengguna

**👥 Manajemen User**
- Kelola semua pengguna
- Approve/reject pendaftaran
- Reset password
- Atur role dan permission

**🎓 Data Master**
- Kelola data siswa
- Kelola data guru
- Kelola data kelas
- Kelola tahun ajaran

**📋 Jenis Pelanggaran & Prestasi**
- Atur kategori pelanggaran
- Atur poin pelanggaran
- Atur jenis prestasi
- Atur poin prestasi

**⚠️ Verifikasi Data**
- Verifikasi pelanggaran
- Verifikasi prestasi
- Approve biodata orang tua

**📄 Laporan**
- Generate laporan PDF
- Export data
- Statistik lengkap

### 5.2 Mengelola Data Siswa

**Menambah Siswa Baru:**

1. **Akses Menu Siswa**
   ```
   Sidebar → Data Master → Siswa
   ```

2. **Klik Tombol "Tambah Siswa"**
   - Tombol berwarna biru di pojok kanan atas

3. **Isi Form Data Siswa**
   ```
   NIS: [Nomor Induk Siswa - Wajib Unik]
   Nama Lengkap: [Nama siswa sesuai dokumen]
   Kelas: [Pilih dari dropdown]
   Jenis Kelamin: [Laki-laki/Perempuan]
   Tahun Ajaran: [Pilih tahun ajaran aktif]
   Alamat: [Alamat lengkap siswa]
   No. Telepon: [Nomor yang bisa dihubungi]
   Email: [Email untuk akun siswa - opsional]
   ```

4. **Validasi Data**
   - Pastikan NIS belum digunakan
   - Nama tidak boleh kosong
   - Kelas harus dipilih
   - Format email harus benar

5. **Simpan Data**
   - Klik tombol "Simpan"
   - Sistem akan membuat akun user otomatis
   - Password default: "password"

**Mengedit Data Siswa:**

1. **Cari Siswa**
   - Gunakan fitur search/filter
   - Atau scroll untuk mencari

2. **Klik Tombol Edit**
   - Tombol berwarna kuning dengan icon pensil

3. **Ubah Data yang Diperlukan**
   - Edit field yang ingin diubah
   - Pastikan data valid

4. **Simpan Perubahan**
   - Klik "Update"
   - Konfirmasi perubahan

**Menghapus Data Siswa:**

⚠️ **Peringatan**: Menghapus siswa akan menghapus semua data terkait!

1. **Klik Tombol Hapus**
   - Tombol merah dengan icon trash

2. **Konfirmasi Penghapusan**
   - Baca peringatan dengan teliti
   - Ketik "HAPUS" untuk konfirmasi

3. **Data Terhapus**
   - Siswa dan akun user terhapus
   - Riwayat pelanggaran/prestasi terhapus

### 5.3 Mengelola Data Guru

**Menambah Guru Baru:**

1. **Akses Menu Guru**
   ```
   Sidebar → Data Master → Guru
   ```

2. **Klik "Tambah Guru"**

3. **Isi Form Data Guru**
   ```
   NIP: [Nomor Induk Pegawai]
   Nama Guru: [Nama lengkap]
   Bidang Studi: [Mata pelajaran yang diampu]
   Jenis Kelamin: [L/P]
   Status: [Aktif/Tidak Aktif]
   Email: [Email untuk akun guru]
   No. Telepon: [Kontak guru]
   ```

4. **Simpan Data**
   - Sistem akan membuat akun user
   - Role default: "guru"

### 5.4 Verifikasi Pelanggaran

**Proses Verifikasi:**

1. **Akses Menu Pelanggaran**
   ```
   Sidebar → Pelanggaran
   ```

2. **Filter Data Pending**
   - Klik tab "Menunggu Verifikasi"
   - Atau filter status "Pending"

3. **Review Data Pelanggaran**
   ```
   Siswa: [Nama siswa yang melanggar]
   Jenis Pelanggaran: [Kategori pelanggaran]
   Poin: [Poin yang akan ditambahkan]
   Keterangan: [Detail kejadian]
   Diinput oleh: [Guru yang menginput]
   Tanggal: [Kapan terjadi]
   ```

4. **Ambil Keputusan**
   
   **Jika SETUJUI:**
   - Klik tombol "Verifikasi"
   - Pilih "Setujui"
   - Poin akan ditambahkan ke siswa
   - Status berubah menjadi "Diverifikasi"
   
   **Jika TOLAK:**
   - Klik tombol "Verifikasi"
   - Pilih "Tolak"
   - Isi alasan penolakan
   - Status berubah menjadi "Ditolak"
   
   **Jika REVISI:**
   - Klik tombol "Verifikasi"
   - Pilih "Revisi"
   - Isi catatan revisi
   - Guru dapat memperbaiki data

5. **Sistem Otomatis Cek Sanksi**
   - Jika total poin siswa ≥ 100
   - Sanksi otomatis dibuat
   - Email notifikasi terkirim

### 5.5 Mengelola Sanksi

**Melihat Sanksi Otomatis:**

1. **Akses Menu Sanksi**
   ```
   Sidebar → Sanksi
   ```

2. **Review Sanksi Baru**
   ```
   Status: Direncanakan
   Siswa: [Nama siswa]
   Total Poin: [Poin pelanggaran]
   Jenis Sanksi: [Otomatis berdasarkan poin]
   ```

3. **Atur Jadwal Sanksi**
   - Klik "Edit Sanksi"
   - Tentukan tanggal mulai
   - Tentukan tanggal selesai
   - Isi detail pelaksanaan

4. **Update Status Sanksi**
   - Direncanakan → Sedang Dilaksanakan
   - Sedang Dilaksanakan → Selesai

### 5.6 Approve Biodata Orang Tua

**Proses Approval:**

1. **Akses Menu Biodata Ortu**
   ```
   Sidebar → Biodata Orang Tua
   ```

2. **Filter Data Pending**
   - Lihat biodata yang belum diapprove

3. **Review Dokumen**
   ```
   Nama Orang Tua: [Sesuai KTP]
   NIK: [16 digit]
   Upload KTP: [Cek kejelasan foto]
   Upload KK: [Cek nama anak ada]
   Alamat: [Sesuai dokumen]
   No. Telepon: [Valid]
   ```

4. **Ambil Keputusan**
   
   **Jika APPROVE:**
   - Semua dokumen lengkap dan valid
   - Klik "Approve"
   - Orang tua bisa akses penuh
   
   **Jika REJECT:**
   - Ada dokumen tidak valid
   - Klik "Reject"
   - Isi alasan penolakan
   - Orang tua harus perbaiki

---

## 6. PANDUAN UNTUK STAFF KESISWAAN

### 6.1 Perbedaan dengan Administrator

Staff Kesiswaan memiliki akses hampir sama dengan Administrator, kecuali:
- Tidak bisa mengelola user admin lain
- Tidak bisa mengakses sistem backup
- Fokus pada data kesiswaan

### 6.2 Tugas Utama

**Harian:**
- Verifikasi pelanggaran & prestasi
- Approve biodata orang tua
- Monitor sanksi aktif
- Respon komunikasi darurat

**Mingguan:**
- Generate laporan mingguan
- Review trend pelanggaran
- Koordinasi dengan wali kelas

**Bulanan:**
- Laporan bulanan ke kepala sekolah
- Evaluasi efektivitas sanksi
- Update data master jika perlu

### 6.3 Workflow Harian

```
08:00 - Login dan cek dashboard
08:15 - Review pelanggaran pending (prioritas tinggi)
08:30 - Review prestasi pending
09:00 - Approve biodata ortu baru
09:30 - Monitor sanksi yang sedang berjalan
10:00 - Koordinasi dengan guru/wali kelas
11:00 - Input data jika diperlukan
14:00 - Generate laporan harian
15:00 - Backup dan logout
```

---

## 7. PANDUAN UNTUK GURU

### 7.1 Menu yang Tersedia

**📊 Dashboard Guru**
- Statistik pelanggaran yang diinput
- Statistik prestasi yang diinput
- Status verifikasi data

**⚠️ Input Pelanggaran**
- Form input pelanggaran siswa
- Riwayat pelanggaran yang diinput

**🏆 Input Prestasi**
- Form input prestasi siswa
- Riwayat prestasi yang diinput

### 7.2 Menginput Pelanggaran

**Langkah-langkah Detail:**

1. **Akses Menu Pelanggaran**
   ```
   Sidebar → Pelanggaran → Tambah Pelanggaran
   ```

2. **Pilih Siswa**
   - Ketik nama siswa di search box
   - Atau pilih dari dropdown
   - Sistem akan menampilkan kelas siswa

3. **Pilih Jenis Pelanggaran**
   ```
   Kategori tersedia:
   - Kedisiplinan (5-15 poin)
   - Ketertiban (10-25 poin)  
   - Sopan Santun (15-30 poin)
   - Keamanan (20-50 poin)
   - Berat (50-100 poin)
   ```

4. **Isi Detail Pelanggaran**
   ```
   Tanggal Kejadian: [Pilih tanggal]
   Waktu: [Jam kejadian]
   Tempat: [Lokasi kejadian]
   Keterangan: [Deskripsi detail kejadian]
   Saksi: [Jika ada saksi]
   ```

5. **Upload Bukti (Opsional)**
   - Foto kejadian
   - Dokumen pendukung
   - Format: JPG, PNG, PDF
   - Maksimal 2MB per file

6. **Review dan Simpan**
   - Periksa kembali semua data
   - Pastikan akurat dan objektif
   - Klik "Simpan"
   - Status: "Menunggu Verifikasi"

**Tips Input Pelanggaran:**
✅ Tulis keterangan yang jelas dan objektif  
✅ Sertakan bukti jika memungkinkan  
✅ Input segera setelah kejadian  
✅ Hindari bahasa yang emosional  

### 7.3 Menginput Prestasi

**Langkah-langkah:**

1. **Akses Menu Prestasi**
   ```
   Sidebar → Prestasi → Tambah Prestasi
   ```

2. **Pilih Siswa**
   - Sama seperti input pelanggaran

3. **Pilih Jenis Prestasi**
   ```
   Tingkat Prestasi:
   - Sekolah (5-10 poin)
   - Kecamatan (10-20 poin)
   - Kabupaten/Kota (20-35 poin)
   - Provinsi (35-50 poin)
   - Nasional (50-75 poin)
   - Internasional (75-100 poin)
   
   Kategori:
   - Akademik
   - Olahraga
   - Seni & Budaya
   - Teknologi
   - Kepemimpinan
   - Lainnya
   ```

4. **Isi Detail Prestasi**
   ```
   Nama Lomba/Kegiatan: [Nama resmi]
   Penyelenggara: [Institusi penyelenggara]
   Tanggal: [Tanggal pelaksanaan]
   Peringkat/Juara: [1, 2, 3, atau Harapan]
   Keterangan: [Detail prestasi]
   ```

5. **Upload Sertifikat**
   - Wajib upload sertifikat/piagam
   - Format: JPG, PNG, PDF
   - Pastikan foto jelas dan terbaca

6. **Simpan Data**
   - Review sekali lagi
   - Klik "Simpan"
   - Status: "Menunggu Verifikasi"

### 7.4 Monitoring Status Verifikasi

**Cek Status Data:**

1. **Dashboard Guru**
   - Lihat ringkasan status verifikasi

2. **Detail Status**
   ```
   🟡 Menunggu: Belum diverifikasi admin
   🟢 Diverifikasi: Sudah disetujui
   🔴 Ditolak: Ditolak dengan alasan
   🔄 Revisi: Perlu diperbaiki
   ```

3. **Jika Ditolak/Revisi**
   - Baca alasan dari admin
   - Perbaiki data sesuai catatan
   - Submit ulang

---

## 8. PANDUAN UNTUK WALI KELAS

### 8.1 Menu Khusus Wali Kelas

**📊 Dashboard Kelas**
- Statistik siswa di kelas
- Grafik pelanggaran kelas
- Grafik prestasi kelas
- Siswa bermasalah

**👥 Data Siswa Kelas**
- Daftar siswa di kelas
- Profile lengkap siswa
- Riwayat pelanggaran & prestasi

**💬 Komunikasi Orang Tua**
- Kirim pesan ke orang tua
- Riwayat komunikasi
- Panggilan orang tua

**📄 Laporan Kelas**
- Laporan khusus kelas
- Export data kelas

### 8.2 Mengelola Kelas

**Melihat Data Kelas:**

1. **Dashboard Kelas**
   ```
   Total Siswa: [Jumlah siswa di kelas]
   Siswa Bermasalah: [Poin tinggi]
   Siswa Berprestasi: [Prestasi terbanyak]
   Rata-rata Poin: [Poin pelanggaran kelas]
   ```

2. **Analisis Trend**
   - Grafik pelanggaran per bulan
   - Grafik prestasi per bulan
   - Perbandingan dengan kelas lain

**Monitoring Siswa:**

1. **Akses Data Siswa Kelas**
   ```
   Sidebar → Data Siswa Kelas
   ```

2. **Filter dan Sorting**
   ```
   Filter berdasarkan:
   - Poin pelanggaran (tinggi ke rendah)
   - Prestasi (banyak ke sedikit)
   - Absensi
   - Status sanksi
   ```

3. **Detail Siswa**
   - Klik nama siswa untuk detail
   - Lihat riwayat lengkap
   - Analisis perkembangan

### 8.3 Komunikasi dengan Orang Tua

**Jenis Komunikasi:**

1. **Pesan Biasa**
   - Informasi umum
   - Pemberitahuan kegiatan
   - Update perkembangan

2. **Pembinaan**
   - Terkait pelanggaran
   - Perlu perhatian khusus
   - Koordinasi penanganan

3. **Panggilan Orang Tua**
   - Masalah serius
   - Perlu bertemu langsung
   - Jadwal dan agenda jelas

**Cara Mengirim Pesan:**

1. **Akses Menu Komunikasi**
   ```
   Sidebar → Komunikasi Orang Tua
   ```

2. **Klik "Kirim Pesan Baru"**

3. **Pilih Siswa**
   - Sistem otomatis mengirim ke orang tua siswa
   - Pastikan siswa sudah terhubung dengan ortu

4. **Pilih Jenis Komunikasi**
   - Pesan Biasa
   - Pembinaan  
   - Panggilan Orang Tua

5. **Tulis Pesan**
   ```
   Judul: [Judul yang jelas]
   Isi Pesan: [Detail komunikasi]
   
   Template Pembinaan:
   "Yth. Bapak/Ibu Orang Tua [Nama Siswa],
   
   Kami ingin menginformasikan bahwa anak Bapak/Ibu 
   [detail masalah/prestasi]. 
   
   Mohon kerja sama untuk [tindak lanjut yang diharapkan].
   
   Terima kasih atas perhatiannya.
   
   Hormat kami,
   [Nama Wali Kelas]"
   ```

6. **Kirim Pesan**
   - Review sekali lagi
   - Klik "Kirim"
   - Orang tua akan mendapat notifikasi

**Monitoring Respon:**

1. **Status Pesan**
   ```
   📤 Terkirim: Pesan sudah dikirim
   👁️ Dibaca: Orang tua sudah membaca
   💬 Dibalas: Orang tua sudah membalas
   ```

2. **Baca Balasan**
   - Klik pesan yang dibalas
   - Baca respon orang tua
   - Balas jika perlu tindak lanjut

### 8.4 Laporan Kelas

**Generate Laporan Kelas:**

1. **Akses Menu Laporan Kelas**
   ```
   Sidebar → Laporan Kelas
   ```

2. **Pilih Periode**
   ```
   Tanggal Mulai: [Awal periode]
   Tanggal Selesai: [Akhir periode]
   ```

3. **Pilih Jenis Laporan**
   - Laporan Lengkap Kelas
   - Laporan Pelanggaran Kelas
   - Laporan Prestasi Kelas
   - Laporan Komunikasi Ortu

4. **Export PDF**
   - Klik "Generate PDF"
   - File akan terdownload
   - Siap untuk dicetak/dibagikan

---

## 9. PANDUAN UNTUK GURU BK

### 9.1 Menu Khusus BK

**📊 Dashboard BK**
- Statistik siswa bermasalah
- Siswa yang perlu konseling
- Jadwal sesi BK

**💬 Bimbingan Konseling**
- Input sesi konseling
- Riwayat konseling siswa
- Follow-up konseling

**📋 Data Siswa Bermasalah**
- Siswa dengan poin tinggi
- Siswa dengan sanksi aktif
- Rekomendasi konseling

### 9.2 Input Sesi Bimbingan Konseling

**Langkah-langkah:**

1. **Akses Menu BK**
   ```
   Sidebar → Bimbingan Konseling → Tambah Sesi
   ```

2. **Pilih Siswa**
   - Cari siswa yang akan dikonseling
   - Sistem menampilkan riwayat BK sebelumnya

3. **Pilih Kategori Konseling**
   ```
   📚 Akademik:
   - Kesulitan belajar
   - Motivasi belajar rendah
   - Prestasi menurun
   
   👥 Sosial:
   - Konflik dengan teman
   - Kesulitan bergaul
   - Bullying
   
   🧠 Pribadi:
   - Masalah emosi
   - Kepercayaan diri
   - Masalah keluarga
   
   🎯 Karir:
   - Pemilihan jurusan
   - Rencana masa depan
   - Minat dan bakat
   ```

4. **Isi Detail Konseling**
   ```
   Tanggal Konseling: [Tanggal sesi]
   Durasi: [Lama konseling dalam menit]
   
   Permasalahan:
   [Deskripsi masalah yang dihadapi siswa]
   
   Analisis:
   [Analisis BK terhadap masalah]
   
   Solusi/Saran:
   [Solusi yang diberikan kepada siswa]
   
   Tindak Lanjut:
   [Rencana follow-up selanjutnya]
   
   Catatan Khusus:
   [Catatan penting lainnya]
   ```

5. **Simpan Data**
   - Review data konseling
   - Klik "Simpan"
   - Data tersimpan dalam riwayat siswa

### 9.3 Monitoring Siswa Bermasalah

**Identifikasi Siswa Prioritas:**

1. **Dashboard BK**
   - Lihat daftar siswa dengan poin tinggi
   - Siswa dengan sanksi aktif
   - Rekomendasi sistem

2. **Filter Siswa**
   ```
   Berdasarkan Poin:
   - 50-75 poin: Perhatian khusus
   - 75-100 poin: Konseling wajib
   - >100 poin: Konseling intensif
   
   Berdasarkan Jenis Pelanggaran:
   - Kekerasan: Prioritas tinggi
   - Narkoba: Prioritas sangat tinggi
   - Bullying: Konseling segera
   ```

3. **Rencana Konseling**
   - Buat jadwal konseling rutin
   - Koordinasi dengan wali kelas
   - Libatkan orang tua jika perlu

### 9.4 Follow-up Konseling

**Monitoring Perkembangan:**

1. **Riwayat Konseling**
   - Lihat sesi konseling sebelumnya
   - Analisis perkembangan siswa
   - Evaluasi efektivitas solusi

2. **Sesi Lanjutan**
   - Jadwalkan sesi follow-up
   - Update progress siswa
   - Sesuaikan strategi konseling

3. **Koordinasi Tim**
   - Komunikasi dengan wali kelas
   - Laporan ke kepala sekolah
   - Koordinasi dengan orang tua

---

## 10. PANDUAN UNTUK SISWA

### 10.1 Menu untuk Siswa

**📊 Dashboard Siswa**
- Total poin pelanggaran
- Total prestasi
- Sanksi aktif (jika ada)
- Riwayat terbaru

**👤 Profil Siswa**
- Data pribadi
- Edit informasi kontak
- Ganti password

### 10.2 Melihat Data Pribadi

**Dashboard Siswa:**

1. **Statistik Pribadi**
   ```
   ⚠️ Total Poin Pelanggaran: [Jumlah poin]
   🏆 Total Prestasi: [Jumlah prestasi]
   ⚖️ Sanksi Aktif: [Jika ada sanksi]
   📅 Bulan Ini: [Aktivitas bulan ini]
   ```

2. **Riwayat Pelanggaran**
   ```
   Tanggal | Jenis Pelanggaran | Poin | Status
   [List pelanggaran dengan detail]
   ```

3. **Riwayat Prestasi**
   ```
   Tanggal | Jenis Prestasi | Tingkat | Poin
   [List prestasi dengan detail]
   ```

4. **Sanksi Aktif**
   ```
   Jenis Sanksi: [Deskripsi sanksi]
   Tanggal Mulai: [Kapan dimulai]
   Tanggal Selesai: [Kapan berakhir]
   Status: [Sedang berjalan/Selesai]
   ```

### 10.3 Update Profil

**Edit Data Pribadi:**

1. **Akses Menu Profil**
   ```
   Sidebar → Profil
   ```

2. **Data yang Bisa Diedit**
   ```
   ✅ No. Telepon
   ✅ Email
   ✅ Alamat
   ✅ Password
   
   ❌ Nama (hanya admin)
   ❌ NIS (hanya admin)
   ❌ Kelas (hanya admin)
   ```

3. **Ganti Password**
   ```
   Password Lama: [Password saat ini]
   Password Baru: [Password baru]
   Konfirmasi: [Ulangi password baru]
   ```

4. **Simpan Perubahan**
   - Klik "Update Profil"
   - Konfirmasi perubahan

### 10.4 Tips untuk Siswa

**Memantau Poin Pelanggaran:**
✅ Cek dashboard secara rutin  
✅ Perhatikan jika poin mendekati 100  
✅ Konsultasi dengan wali kelas jika perlu  
✅ Perbaiki perilaku untuk menghindari sanksi  

**Meningkatkan Prestasi:**
✅ Aktif dalam kegiatan sekolah  
✅ Ikuti lomba/kompetisi  
✅ Dokumentasikan setiap prestasi  
✅ Minta guru untuk input prestasi  

---

## 11. PANDUAN UNTUK ORANG TUA

### 11.1 Pendaftaran Akun Orang Tua

**Langkah Pendaftaran:**

1. **Akses Halaman Register**
   ```
   URL → Klik "Register" → Pilih "Orang Tua"
   ```

2. **Isi Form Pendaftaran**
   ```
   Email: [Email aktif orang tua]
   Password: [Password yang kuat]
   Konfirmasi Password: [Ulangi password]
   Nama Anak: [Nama lengkap anak di sekolah]
   NIS Anak: [Nomor Induk Siswa anak]
   ```

3. **Validasi Otomatis**
   - Sistem akan memvalidasi nama dan NIS anak
   - Pastikan data sesuai dengan data di sekolah
   - Jika tidak cocok, hubungi admin sekolah

4. **Akun Dibuat**
   - Status: Pending approval
   - Tunggu verifikasi admin
   - Cek email untuk konfirmasi

### 11.2 Melengkapi Biodata (Wajib)

**Setelah Login Pertama:**

1. **Modal Biodata Muncul Otomatis**
   - Tidak bisa dilewati
   - Harus dilengkapi untuk akses penuh

2. **Isi Data Lengkap**
   ```
   Nama Ayah: [Nama lengkap ayah]
   Nama Ibu: [Nama lengkap ibu]
   NIK: [16 digit NIK sesuai KTP]
   Alamat Lengkap: [Alamat sesuai KK]
   No. Telepon: [Nomor yang aktif]
   Pekerjaan Ayah: [Pekerjaan ayah]
   Pekerjaan Ibu: [Pekerjaan ibu]
   ```

3. **Upload Dokumen Wajib**
   ```
   📄 KTP Orang Tua:
   - Format: JPG, PNG, PDF
   - Maksimal 2MB
   - Foto harus jelas dan terbaca
   
   📄 Kartu Keluarga (KK):
   - Format: JPG, PNG, PDF
   - Maksimal 2MB
   - Pastikan nama anak tertera
   ```

4. **Submit Biodata**
   - Review semua data
   - Pastikan dokumen sudah diupload
   - Klik "Submit"
   - Status: Menunggu approval admin

### 11.3 Dashboard Orang Tua

**Setelah Biodata Diapprove:**

1. **Statistik Anak**
   ```
   👨‍🎓 Data Anak:
   - Nama: [Nama anak]
   - Kelas: [Kelas saat ini]
   - NIS: [Nomor induk]
   
   ⚠️ Pelanggaran:
   - Total Poin: [Jumlah poin]
   - Bulan Ini: [Pelanggaran bulan ini]
   - Status: [Normal/Perhatian/Bahaya]
   
   🏆 Prestasi:
   - Total Prestasi: [Jumlah prestasi]
   - Bulan Ini: [Prestasi bulan ini]
   - Poin Prestasi: [Total poin prestasi]
   
   ⚖️ Sanksi:
   - Sanksi Aktif: [Jika ada]
   - Riwayat Sanksi: [Sanksi sebelumnya]
   ```

2. **Grafik Perkembangan**
   - Trend pelanggaran per bulan
   - Trend prestasi per bulan
   - Perbandingan dengan rata-rata kelas

### 11.4 Komunikasi dengan Sekolah

**Membaca Pesan dari Sekolah:**

1. **Akses Menu Pesan**
   ```
   Sidebar → Pesan & Pembinaan
   ```

2. **Jenis Pesan**
   ```
   📧 Pesan Biasa:
   - Informasi umum
   - Pemberitahuan kegiatan
   - Update perkembangan
   
   📝 Pembinaan:
   - Terkait pelanggaran anak
   - Perlu perhatian khusus
   - Koordinasi penanganan
   
   📞 Panggilan Orang Tua:
   - Masalah serius
   - Perlu bertemu langsung
   - Jadwal dan agenda
   ```

3. **Membaca Detail Pesan**
   - Klik pesan untuk membaca
   - Status berubah menjadi "Dibaca"
   - Perhatikan jenis dan prioritas

**Membalas Pesan:**

1. **Klik Tombol "Balas"**

2. **Tulis Balasan**
   ```
   Template Balasan:
   "Terima kasih atas informasinya.
   
   [Respon terhadap isi pesan]
   
   [Komitmen tindak lanjut dari orang tua]
   
   Mohon bimbingan lebih lanjut jika diperlukan.
   
   Hormat kami,
   [Nama Orang Tua]"
   ```

3. **Kirim Balasan**
   - Review balasan
   - Klik "Kirim"
   - Status menjadi "Dibalas"

### 11.5 Monitoring Anak

**Tips Monitoring Efektif:**

1. **Cek Dashboard Rutin**
   - Minimal 2x seminggu
   - Perhatikan perubahan poin
   - Monitor trend perilaku

2. **Komunikasi dengan Anak**
   - Diskusikan data di sistem
   - Berikan apresiasi untuk prestasi
   - Bimbing untuk perbaikan perilaku

3. **Koordinasi dengan Sekolah**
   - Respon cepat pesan sekolah
   - Proaktif komunikasi jika ada masalah
   - Hadiri panggilan orang tua

4. **Tindak Lanjut di Rumah**
   - Terapkan konsistensi aturan
   - Berikan reward untuk prestasi
   - Berikan konsekuensi untuk pelanggaran

**Indikator yang Perlu Diperhatikan:**

🟢 **Aman (0-25 poin)**
- Perilaku baik
- Pertahankan kondisi

🟡 **Perhatian (26-50 poin)**
- Mulai ada masalah
- Perlu bimbingan

🟠 **Waspada (51-75 poin)**
- Masalah cukup serius
- Perlu tindakan tegas

🔴 **Bahaya (76-99 poin)**
- Hampir sanksi
- Perlu intervensi intensif

⚫ **Sanksi (≥100 poin)**
- Sanksi otomatis
- Perlu evaluasi menyeluruh

---

## 12. FITUR LAPORAN

### 12.1 Jenis Laporan

**Laporan Siswa:**
- Data lengkap siswa per kelas
- Riwayat pelanggaran & prestasi
- Status sanksi aktif

**Laporan Pelanggaran:**
- Detail pelanggaran per periode
- Statistik per jenis pelanggaran
- Trend pelanggaran

**Laporan Prestasi:**
- Detail prestasi per periode
- Statistik per jenis prestasi
- Trend prestasi

**Laporan Kelas (Khusus Wali Kelas):**
- Statistik lengkap kelas
- Perbandingan antar siswa
- Komunikasi dengan orang tua

### 12.2 Generate Laporan PDF

**Langkah-langkah:**

1. **Akses Menu Laporan**
   ```
   Sidebar → Laporan
   ```

2. **Pilih Jenis Laporan**
   - Klik tab sesuai jenis yang diinginkan

3. **Set Filter**
   ```
   Kelas: [Pilih kelas atau "Semua Kelas"]
   Tanggal Mulai: [Awal periode laporan]
   Tanggal Selesai: [Akhir periode laporan]
   ```

4. **Preview Data**
   - Sistem menampilkan preview data
   - Pastikan data sesuai filter

5. **Export PDF**
   - Klik "Export PDF"
   - Tunggu proses generate
   - File PDF akan terdownload

### 12.3 Membaca Laporan

**Struktur Laporan PDF:**

1. **Header Laporan**
   ```
   Logo Sekolah
   Nama Sekolah
   Alamat Sekolah
   Judul Laporan
   Periode Laporan
   ```

2. **Ringkasan Statistik**
   ```
   Total Data
   Persentase per Kategori
   Trend Periode
   ```

3. **Detail Data**
   ```
   Tabel lengkap sesuai filter
   Grafik pendukung
   Analisis singkat
   ```

4. **Footer**
   ```
   Tanggal Generate
   User yang Generate
   Tanda Tangan Digital
   ```

---

## 13. TROUBLESHOOTING

### 13.1 Masalah Login

**Problem: Email/Password Salah**

Solusi:
1. Periksa kembali email dan password
2. Pastikan Caps Lock tidak aktif
3. Coba copy-paste email dari dokumen
4. Hubungi admin untuk reset password

**Problem: Akun Belum Diverifikasi**

Solusi:
1. Cek email untuk link verifikasi
2. Klik link verifikasi di email
3. Jika tidak ada email, hubungi admin
4. Tunggu proses approval admin

**Problem: Page Expired**

Solusi:
1. Refresh halaman (F5)
2. Clear browser cache (Ctrl+Shift+Del)
3. Login ulang
4. Gunakan browser berbeda

### 13.2 Masalah Input Data

**Problem: Data Tidak Tersimpan**

Solusi:
1. Periksa koneksi internet
2. Pastikan semua field wajib terisi
3. Cek format data (email, tanggal, dll)
4. Refresh halaman dan coba lagi

**Problem: Siswa Tidak Ditemukan**

Solusi:
1. Pastikan nama siswa benar
2. Cek ejaan nama
3. Gunakan NIS untuk pencarian
4. Hubungi admin jika siswa belum terdaftar

**Problem: Upload File Gagal**

Solusi:
1. Cek ukuran file (maksimal 2MB)
2. Cek format file (JPG, PNG, PDF)
3. Gunakan file dengan nama sederhana
4. Coba compress file jika terlalu besar

### 13.3 Masalah Tampilan

**Problem: Tampilan Berantakan**

Solusi:
1. Refresh halaman (Ctrl+F5)
2. Clear browser cache
3. Update browser ke versi terbaru
4. Gunakan browser yang didukung

**Problem: Menu Tidak Muncul**

Solusi:
1. Cek role/permission user
2. Login ulang
3. Hubungi admin untuk cek akses
4. Gunakan browser berbeda

### 13.4 Masalah Notifikasi

**Problem: Email Tidak Terkirim**

Solusi:
1. Cek folder spam/junk
2. Pastikan email aktif
3. Hubungi admin untuk cek konfigurasi
4. Update email di profil

**Problem: Notifikasi Tidak Muncul**

Solusi:
1. Refresh halaman
2. Cek pengaturan browser
3. Allow notifikasi dari website
4. Login ulang

---

## 14. FAQ (Frequently Asked Questions)

### 14.1 Pertanyaan Umum

**Q: Bagaimana cara mendapatkan akun?**
A: Hubungi administrator sekolah untuk dibuatkan akun sesuai role Anda.

**Q: Bisakah satu orang memiliki multiple role?**
A: Ya, dengan persetujuan admin. Misalnya guru yang juga wali kelas.

**Q: Apakah data aman?**
A: Ya, sistem menggunakan enkripsi dan backup otomatis untuk keamanan data.

**Q: Bisakah diakses dari HP?**
A: Ya, sistem responsive dan bisa diakses dari smartphone/tablet.

### 14.2 Pertanyaan Teknis

**Q: Browser apa yang direkomendasikan?**
A: Google Chrome versi terbaru untuk performa optimal.

**Q: Bagaimana jika lupa password?**
A: Hubungi administrator untuk reset password.

**Q: Bisakah data diekspor?**
A: Ya, tersedia fitur export PDF untuk laporan.

**Q: Apakah ada backup data?**
A: Ya, sistem melakukan backup otomatis setiap hari.

### 14.3 Pertanyaan Fungsional

**Q: Kapan sanksi otomatis dibuat?**
A: Ketika total poin pelanggaran siswa mencapai 100 atau lebih.

**Q: Bisakah pelanggaran dibatalkan?**
A: Hanya admin/kesiswaan yang bisa menolak pelanggaran saat verifikasi.

**Q: Bagaimana cara menambah jenis pelanggaran baru?**
A: Hubungi administrator untuk menambah jenis pelanggaran.

**Q: Bisakah orang tua melihat nilai akademik?**
A: Sistem ini khusus untuk kesiswaan, bukan nilai akademik.

---

## 📞 KONTAK & BANTUAN

### Kontak Administrator
**Email:** admin@sekolah.sch.id  
**Telepon:** (021) 1234-5678  
**Jam Kerja:** Senin-Jumat, 07:00-15:00  

### Bantuan Teknis
**Email:** support@sekolah.sch.id  
**WhatsApp:** 0812-3456-7890  

### Dokumentasi Tambahan
- **USER_GUIDE.md** - Panduan ringkas
- **QUICK_START.md** - Panduan cepat 5 menit
- **WORKFLOW.md** - Alur kerja sistem
- **docs/INDEX.md** - Dokumentasi teknis lengkap

---

## 📋 CHECKLIST PENGGUNAAN

### Untuk Administrator
- [ ] Login dan cek dashboard harian
- [ ] Verifikasi pelanggaran pending
- [ ] Verifikasi prestasi pending  
- [ ] Approve biodata orang tua baru
- [ ] Monitor sanksi aktif
- [ ] Backup data (otomatis)
- [ ] Generate laporan mingguan

### Untuk Guru
- [ ] Input pelanggaran segera setelah kejadian
- [ ] Input prestasi siswa
- [ ] Cek status verifikasi data
- [ ] Upload bukti pendukung
- [ ] Koordinasi dengan wali kelas

### Untuk Wali Kelas
- [ ] Monitor statistik kelas
- [ ] Komunikasi dengan orang tua
- [ ] Follow-up siswa bermasalah
- [ ] Generate laporan kelas
- [ ] Koordinasi dengan guru BK

### Untuk Orang Tua
- [ ] Cek dashboard anak rutin
- [ ] Baca pesan dari sekolah
- [ ] Balas komunikasi sekolah
- [ ] Monitor perkembangan anak
- [ ] Koordinasi tindak lanjut di rumah

---

**© 2025 SIKAP - Sistem Informasi Kesiswaan dan Prestasi**  
*Dikembangkan untuk Kemajuan Pendidikan Indonesia*

---

*Panduan ini akan terus diperbarui sesuai perkembangan sistem. Untuk versi terbaru, silakan cek dokumentasi online atau hubungi administrator.*