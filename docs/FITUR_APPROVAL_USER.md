# Fitur Approval User untuk Siswa dan Orang Tua

## Deskripsi
Sistem approval user memungkinkan admin untuk menyetujui atau menolak pendaftaran user dengan role **siswa** dan **orang tua**. User yang sudah disetujui dapat melihat data mereka tanpa perlu verifikasi email.

## Fitur Utama

### 1. Registrasi User (Siswa/Orang Tua)
- User mendaftar melalui halaman `/signup`
- Status awal: **pending**
- Tidak perlu verifikasi email
- Setelah registrasi, user langsung login tapi belum bisa akses data

### 2. Dashboard Siswa
**Sebelum Approval:**
- Menampilkan pesan "Menunggu Persetujuan Admin"
- Status: PENDING
- Tidak dapat melihat data pelanggaran/prestasi

**Setelah Approval:**
- Menampilkan profil siswa lengkap
- Statistik: Total Pelanggaran, Prestasi, Poin, Sanksi
- Riwayat pelanggaran terbaru (terverifikasi)
- Riwayat prestasi terbaru (terverifikasi)
- Daftar sanksi aktif

### 3. Dashboard Orang Tua
**Sebelum Approval:**
- Menampilkan pesan "Menunggu Persetujuan Admin"
- Status: PENDING
- Tidak dapat melihat data anak

**Setelah Approval:**
- Menampilkan profil anak lengkap
- Statistik anak: Total Pelanggaran, Prestasi, Poin, Sanksi
- Riwayat pelanggaran anak (terverifikasi)
- Riwayat prestasi anak (terverifikasi)
- Daftar sanksi aktif anak

### 4. Halaman Persetujuan User (Admin)
**Akses:** Menu Sidebar > System > Persetujuan User

**Fitur:**
- Tab "User Menunggu Persetujuan" - Daftar user pending
- Tab "User Disetujui" - Riwayat user yang disetujui
- Tab "User Ditolak" - Riwayat user yang ditolak

**Aksi Admin:**
- **Setujui:** Approve user, status berubah jadi `approved`
- **Tolak:** Reject user dengan alasan penolakan

## Alur Kerja

```
1. User Registrasi (siswa/ortu)
   ↓
2. Status: PENDING
   ↓
3. Login berhasil, tapi data belum tampil
   ↓
4. Admin review di halaman "Persetujuan User"
   ↓
5a. Admin SETUJUI → Status: APPROVED → User bisa akses data
5b. Admin TOLAK → Status: REJECTED → User tidak bisa akses data
```

## File yang Dibuat/Dimodifikasi

### Controller
- `app/Http/Controllers/DashboardController.php` - Update siswaDashboard() dan ortuDashboard()
- `app/Http/Controllers/AuthController.php` - Update publicRegister()
- `app/Http/Controllers/UserApprovalController.php` - NEW (Controller approval)

### Middleware
- `app/Http/Middleware/CheckUserApproved.php` - NEW (Cek status approval)

### View
- `resources/views/dashboard/siswa.blade.php` - Update tampilan
- `resources/views/dashboard/ortu.blade.php` - Update tampilan
- `resources/views/admin/user-approval.blade.php` - NEW (Halaman approval)

### Routes
- `routes/web.php` - Tambah route approval

### Layout
- `resources/views/layouts/app.blade.php` - Tambah menu "Persetujuan User"

## Cara Penggunaan

### Untuk User (Siswa/Orang Tua)
1. Buka `/signup`
2. Pilih role: Siswa atau Orang Tua
3. Isi form registrasi
4. Klik "Daftar"
5. Login otomatis, tunggu approval dari admin
6. Setelah disetujui, refresh halaman untuk melihat data

### Untuk Admin
1. Login sebagai admin
2. Buka menu: System > Persetujuan User
3. Lihat daftar user pending
4. Klik "Setujui" untuk approve
5. Atau klik "Tolak" dan isi alasan penolakan

## Status User

| Status | Deskripsi |
|--------|-----------|
| `pending` | Menunggu persetujuan admin |
| `approved` | Disetujui, dapat akses data |
| `rejected` | Ditolak, tidak dapat akses data |

## Catatan Penting

1. **Tidak Perlu Verifikasi Email** - User langsung bisa login setelah registrasi
2. **Hanya Data Terverifikasi** - Siswa/Ortu hanya melihat pelanggaran/prestasi yang sudah diverifikasi admin
3. **Admin Tidak Perlu Approval** - Role admin langsung aktif tanpa approval
4. **Metadata Orang Tua** - Relasi orang tua ke siswa disimpan di field `metadata` (JSON)

## Testing

### Test Registrasi Siswa
```
1. Buka /signup?role=siswa
2. Isi: Nama, Email, Password, NIS, Kelas, Jenis Kelamin
3. Submit
4. Cek dashboard → Harus tampil "Menunggu Persetujuan"
```

### Test Approval Admin
```
1. Login sebagai admin
2. Buka /admin/user-approval
3. Setujui user siswa
4. Login sebagai siswa → Data harus tampil
```

### Test Dashboard Orang Tua
```
1. Registrasi sebagai ortu, pilih siswa (anak)
2. Admin approve
3. Login sebagai ortu → Data anak harus tampil
```

## Troubleshooting

**Q: User sudah approved tapi data tidak muncul?**
A: Pastikan relasi siswa sudah dibuat saat registrasi. Cek tabel `siswas` dengan `users_id`.

**Q: Orang tua tidak bisa lihat data anak?**
A: Cek field `metadata` di tabel `users`, pastikan ada `siswa_id`.

**Q: Error saat approve user?**
A: Pastikan field `verified_by` dan `verified_at` ada di tabel `users`.

## Update Database

Pastikan migration sudah dijalankan:
```bash
php artisan migrate
```

Field yang diperlukan di tabel `users`:
- `status` (enum: pending, approved, rejected)
- `verified_by` (foreign key ke users.id)
- `verified_at` (timestamp)
- `rejection_reason` (text, nullable)
- `metadata` (text, nullable)
