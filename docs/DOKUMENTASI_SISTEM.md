# DOKUMENTASI SISTEM KESISWAAN
## Aplikasi Pencatatan Poin Pelanggaran, Sanksi dan Prestasi Siswa

---

## 1. MENU DAN FITUR BERDASARKAN ROLE

### 1.1 ADMIN SISTEM
**Role:** Super User  
**Privilege:** Full Access ke semua menu

#### Menu Utama:
1. **Dashboard**
   - Statistik keseluruhan sistem
   - Grafik pelanggaran & prestasi per bulan
   - Top 5 pelanggar dan berprestasi
   - Data pending verifikasi
   
2. **Manajemen Data Master**
   - Data Guru (CRUD)
   - Data Siswa (CRUD)
   - Data Kelas (CRUD)
   - Data Tahun Ajaran (CRUD)
   - Jenis Pelanggaran (CRUD)
   - Jenis Prestasi (CRUD)
   
3. **Manajemen User**
   - User Management (CRUD)
   - Assign Role & Privilege
   - Reset Password
   
4. **Backup & Restore**
   - Backup Database Manual
   - Restore Database
   - Download Backup
   - Schedule Auto Backup

5. **Laporan**
   - Laporan Pelanggaran
   - Laporan Prestasi
   - Laporan Sanksi
   - Export PDF/Excel

---

### 1.2 KESISWAAN
**Role:** Verifikator & Koordinator Disiplin  
**Privilege:** Full Access kecuali User Management & Backup

#### Menu Utama:
1. **Dashboard**
   - Statistik pelanggaran & prestasi
   - Data pending verifikasi
   - Alert sanksi mendatang
   
2. **Manajemen Pelanggaran**
   - Input Pelanggaran Baru
   - Verifikasi Pelanggaran (Approve/Reject)
   - Lihat Semua Pelanggaran
   - Detail Per Siswa
   
3. **Manajemen Sanksi**
   - Penentuan Sanksi
   - Jadwal Pelaksanaan Sanksi
   - Tracking Progres Sanksi
   - Evaluasi Sanksi
   
4. **Manajemen Prestasi**
   - Input Prestasi
   - Verifikasi Prestasi (Approve/Reject)
   - Lihat Semua Prestasi
   - Detail Per Siswa
   
5. **Monitoring**
   - Monitor Siswa Bermasalah
   - Monitor Sanksi Aktif
   - Monitor Siswa Berprestasi
   
6. **Laporan**
   - Laporan Lengkap Per Periode
   - Statistik & Analytics
   - Export Data

---

### 1.3 GURU
**Role:** Input Data Pelanggaran  
**Privilege:** Limited Access

#### Menu Utama:
1. **Dashboard**
   - Statistik input yang dilakukan
   - Total pelanggaran yang dicatat
   - Data bulan ini
   
2. **Input Pelanggaran**
   - Form Input Pelanggaran
   - Pilih Siswa
   - Pilih Jenis Pelanggaran (Auto Poin)
   - Keterangan Tambahan
   
3. **Riwayat Input**
   - Data Pelanggaran yang Dicatat
   - Status Verifikasi
   - Filter by Status/Tanggal

---

### 1.4 WALI KELAS
**Role:** Monitoring Siswa Binaan  
**Privilege:** Limited to Kelas yang Diampu

#### Menu Utama:
1. **Dashboard**
   - Statistik Kelas Binaan
   - Alert Siswa Bermasalah
   - Siswa Berprestasi
   
2. **Data Siswa Kelas**
   - Daftar Siswa di Kelas
   - Profil Lengkap Siswa
   - Riwayat Pelanggaran Per Siswa
   - Riwayat Prestasi Per Siswa
   
3. **Input Pelanggaran**
   - Input untuk Siswa di Kelasnya
   
4. **Monitoring Sanksi**
   - Sanksi Aktif Siswa di Kelas
   - Progres Penyelesaian
   
5. **Laporan Kelas**
   - Laporan Per Siswa
   - Laporan Kelas Keseluruhan
   - Export Data

---

### 1.5 KONSELOR BK (Bimbingan Konseling)
**Role:** Penanganan Masalah Siswa  
**Privilege:** Akses Data BK & Follow-up Sanksi

#### Menu Utama:
1. **Dashboard BK**
   - Kasus Terdaftar
   - Kasus dalam Proses
   - Kasus Selesai
   
2. **Data Konseling**
   - Input Konseling Individu
   - Input Konseling Kelompok
   - Kasus Siswa
   - Catatan Perkembangan
   
3. **Follow-up Sanksi**
   - Siswa dengan Sanksi Berat
   - Monitoring Perubahan Perilaku
   - Evaluasi Efektivitas Sanksi
   
4. **Laporan BK**
   - Laporan Konseling
   - Trend Masalah Siswa
   - Statistik Penanganan

---

### 1.6 KEPALA SEKOLAH
**Role:** Monitoring & Evaluasi  
**Privilege:** View All Data (Read Only)

#### Menu Utama:
1. **Dashboard Executive**
   - Overview Keseluruhan
   - Grafik Trend
   - KPI (Key Performance Indicator)
   - Alert & Notification
   
2. **Monitoring Pelanggaran**
   - Data Real-time Pelanggaran
   - Siswa dengan Pelanggaran Berat
   - Trend Bulanan
   
3. **Monitoring Sanksi**
   - Sanksi Aktif
   - Efektivitas Sanksi
   - Kasus Eskalasi
   
4. **Monitoring Prestasi**
   - Data Prestasi Siswa
   - Trend Prestasi
   
5. **Laporan Executive**
   - Comprehensive Report
   - Analytics & Insights
   - Data untuk Stakeholder

---

### 1.7 SISWA
**Role:** Subjek Disiplin  
**Privilege:** View Data Pribadi Only

#### Menu Utama:
1. **Dashboard Siswa**
   - Profil Pribadi
   - Total Pelanggaran
   - Total Prestasi
   - Total Poin
   - Status Kategori (Ringan/Sedang/Berat/Sangat Berat)
   
2. **Riwayat Pelanggaran**
   - Data Pelanggaran Pribadi
   - Detail Pelanggaran
   - Poin & Kategori
   
3. **Riwayat Prestasi**
   - Data Prestasi Pribadi
   - Detail Prestasi
   - Poin Reward
   
4. **Sanksi Aktif**
   - Sanksi yang Sedang Berjalan
   - Jadwal Pelaksanaan
   - Progres Penyelesaian
   - Status Sanksi
   
5. **Notifikasi**
   - Pemberitahuan Sanksi Baru
   - Reminder Deadline Sanksi

---

### 1.8 ORANG TUA
**Role:** Monitoring Anak  
**Privilege:** View Data Anak Only

#### Menu Utama:
1. **Dashboard Orang Tua**
   - Profil Anak
   - Statistik Anak
   - Alert & Notifikasi
   
2. **Data Pelanggaran Anak**
   - Riwayat Pelanggaran
   - Detail & Kronologi
   
3. **Data Prestasi Anak**
   - Riwayat Prestasi
   - Detail Prestasi
   
4. **Sanksi Anak**
   - Sanksi Aktif
   - Riwayat Sanksi
   - Status Penyelesaian
   
5. **Notifikasi**
   - Pemberitahuan Pelanggaran Baru
   - Pemberitahuan Sanksi
   - Prestasi Baru

---

## 2. WORKFLOW SISTEM

### 2.1 Workflow Pelanggaran
```
1. Guru/Wali Kelas Input Pelanggaran
   ↓
2. Sistem Auto-calculate Poin
   ↓
3. Status: "Pending Verifikasi"
   ↓
4. Kesiswaan Verifikasi:
   - Approve → Status: "Terverifikasi"
   - Reject → Status: "Ditolak"
   - Revisi → Kembali ke Guru
   ↓
5. Jika Terverifikasi:
   - Poin ditambahkan ke total siswa
   - Trigger auto-create sanksi (opsional)
   - Notifikasi ke Siswa & Orang Tua
```

### 2.2 Workflow Sanksi
```
1. Sanksi dibuat dari:
   - Auto-generate dari pelanggaran
   - Manual oleh Kesiswaan
   ↓
2. Status: "Direncanakan"
   ↓
3. Jadwal Pelaksanaan ditentukan
   ↓
4. Status: "Berjalan"
   ↓
5. Siswa menyelesaikan sanksi
   ↓
6. Bukti pelaksanaan di-upload
   ↓
7. Evaluasi oleh Kesiswaan/BK
   ↓
8. Status: "Selesai" atau "Perpanjangan"
```

### 2.3 Workflow Prestasi
```
1. Kesiswaan/Admin Input Prestasi
   ↓
2. Sistem Auto-calculate Poin Reward
   ↓
3. Status: "Pending Verifikasi"
   ↓
4. Kesiswaan Verifikasi:
   - Approve → Status: "Terverifikasi"
   - Reject → Status: "Ditolak"
   ↓
5. Jika Terverifikasi:
   - Poin reward ditambahkan
   - Notifikasi ke Siswa & Orang Tua
```

---

## 3. STATUS DALAM SISTEM

### 3.1 Status Verifikasi
- **pending**: Data baru, menunggu verifikasi
- **terverifikasi**: Sudah diverifikasi valid
- **ditolak**: Data tidak valid
- **revisi**: Butuh perbaikan data

### 3.2 Status Pelanggaran (berdasarkan Poin)
- **Ringan**: Poin 1-15
- **Sedang**: Poin 16-30
- **Berat**: Poin 31-50
- **Sangat Berat**: Poin 51+

### 3.3 Status Sanksi
- **direncanakan**: Sanksi sudah ditetapkan
- **berjalan**: Sedang dilaksanakan
- **selesai**: Sudah selesai dilaksanakan
- **ditunda**: Ditunda sementara
- **dibatalkan**: Dibatalkan

### 3.4 Status Pelaksanaan Sanksi
- **terjadwal**: Sudah dijadwalkan
- **dikerjakan**: Sedang dikerjakan
- **tuntas**: Sudah selesai
- **terlambat**: Melebihi deadline
- **perpanjangan**: Butuh perpanjangan waktu

### 3.5 Status Siswa
- **aktif**: Siswa aktif
- **lulus**: Sudah lulus
- **pindah**: Pindah sekolah
- **drop_out**: Keluar sekolah
- **cuti**: Sedang cuti

### 3.6 Status BK
- **terdaftar**: Kasus terdaftar
- **diproses**: Sedang ditangani
- **selesai**: Sudah selesai
- **tindak_lanjut**: Butuh follow-up

---

## 4. FITUR LENGKAP SISTEM

### 4.1 Dashboard & Monitoring
- Dashboard Real-time statistik
- Monitoring Sanksi Aktif
- Alert System notifikasi
- Quick Overview kasus prioritas

### 4.2 Manajemen Pelanggaran
- Input Pelanggaran oleh Guru/Wali Kelas/Kesiswaan
- Jenis Pelanggaran dengan poin standar
- Auto-Suggestion Sanksi berdasarkan poin
- Verifikasi Pelanggaran oleh Kesiswaan
- Tracking history pelanggaran

### 4.3 Manajemen Sanksi
- Penentuan Sanksi (manual/otomatis)
- Jadwal Pelaksanaan sanksi
- Tracking Progres penyelesaian
- Upload bukti pelaksanaan
- Evaluasi Sanksi setelah selesai
- Eskalasi untuk kasus berat

### 4.4 Manajemen Prestasi
- Input Prestasi akademik/non-akademik
- Jenis Prestasi dengan poin reward
- Verifikasi Prestasi oleh Kesiswaan
- Poin Reward System

### 4.5 Bimbingan Konseling
- Konseling Individu/Kelompok
- Follow-up Sanksi melalui BK
- Evaluasi Perubahan Perilaku
- Catatan Perkembangan Siswa

### 4.6 Laporan & Analytics
- Laporan Sanksi per siswa/kelas/periode
- Statistik Efektivitas sanksi
- Trend Analysis pelanggaran
- Export Data (PDF/Excel)
- Dashboard Analytics

### 4.7 Sistem & Admin
- Manajemen User multi-level
- Master Data (Guru, Siswa, Kelas, dll)
- Backup & Restore database
- Audit Trail semua aktivitas
- System Configuration

---

## 5. SOLUSI MASALAH "DATA SISWA BELUM TERDAFTAR"

### 5.1 Penyebab Masalah
1. User siswa sudah dibuat di tabel `users`
2. Tapi data siswa di tabel `siswa` belum ada
3. Atau tidak ada relasi `users_id` yang menghubungkan

### 5.2 Solusi yang Diterapkan

#### A. Di Controller (DashboardController.php)
```php
// Cari siswa dengan 2 cara:
$siswa = Siswa::where('users_id', $user->id)
    ->orWhere('nis', $user->username)
    ->first();

// Jika tidak ditemukan
if (!$siswa) {
    return view('dashboard.siswa', [
        'siswa' => null,
        'message' => 'Data siswa Anda belum terdaftar...',
        'totalPelanggaran' => 0,
        'totalPrestasi' => 0,
        'totalPoin' => 0,
        'sanksiAktif' => 0
    ]);
}
```

#### B. Di View (siswa.blade.php)
```blade
@if(!$siswa)
    <!-- Tampilan informasi bahwa data belum terdaftar -->
    <!-- Tampilkan username dan email user -->
    <!-- Petunjuk hubungi admin -->
@else
    <!-- Tampilan dashboard normal -->
@endif
```

### 5.3 Cara Mengisi Data Siswa

#### Opsi 1: Admin/Kesiswaan Input Manual
1. Login sebagai Admin/Kesiswaan
2. Menu **Data Siswa** → **Tambah Siswa**
3. Isi data lengkap siswa
4. **Pilih User** yang sudah ada (relasi users_id)
5. Simpan

#### Opsi 2: Auto-link via Username
Sistem akan otomatis mencari siswa berdasarkan:
- `users_id` yang cocok
- ATAU `nis` yang sama dengan `username`

---

## 6. BEST PRACTICE PENGGUNAAN

### 6.1 Untuk Admin/Kesiswaan
1. **Setup Awal**:
   - Input semua Data Master terlebih dahulu
   - Buat User untuk semua stakeholder
   - Link User dengan data Guru/Siswa

2. **Operational**:
   - Verifikasi data setiap hari
   - Monitor sanksi yang hampir deadline
   - Backup database secara berkala

### 6.2 Untuk Guru/Wali Kelas
1. Input pelanggaran segera setelah kejadian
2. Isi keterangan sejelas mungkin
3. Attach bukti jika ada
4. Cek status verifikasi berkala

### 6.3 Untuk Siswa
1. Login dan cek dashboard secara rutin
2. Monitor sanksi aktif
3. Selesaikan sanksi tepat waktu
4. Upload bukti pelaksanaan sanksi

### 6.4 Untuk Orang Tua
1. Login berkala untuk monitor anak
2. Perhatikan notifikasi sanksi
3. Support anak menyelesaikan sanksi
4. Komunikasi dengan sekolah via sistem

---

## 7. KEAMANAN SISTEM

### 7.1 Authentication
- Login dengan username & password
- Session management
- Remember me token
- Logout otomatis setelah inaktif

### 7.2 Authorization
- Role-based access control
- Privilege checking setiap menu
- Data filtering berdasarkan role
- Audit trail setiap aksi

### 7.3 Data Protection
- Password hashing (bcrypt)
- SQL injection prevention
- XSS protection
- CSRF token
- Input validation
- Backup encryption

---

## 8. MAINTENANCE

### 8.1 Backup Rutin
- Daily incremental backup (00:00)
- Weekly full backup (Minggu 02:00)
- Monthly archive backup (Akhir bulan)
- Keep backup 30 hari terakhir

### 8.2 Database Cleanup
- Hapus data lama (sesuai kebijakan)
- Optimize table berkala
- Reindex database
- Vacuum database

### 8.3 Monitoring
- Monitor disk space
- Monitor database size
- Monitor system performance
- Check error logs

---

## KONTAK SUPPORT

Jika ada masalah:
1. Hubungi Admin Sistem
2. Hubungi Bagian Kesiswaan
3. Email: admin@sekolah.sch.id
4. Telp: (021) xxxx-xxxx

---

**Dokumentasi ini dibuat sesuai dengan:**
- Modul UJI KOMPETENSI KEAHLIAN P1-25/26
- Kode: KM3063
- Tahun Pelajaran 2025/2026
