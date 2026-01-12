# Perbaikan Menu Guru

## Masalah yang Diperbaiki

**Error:** `Field 'users_id' doesn't have a default value`

**Penyebab:** Form create dan edit tidak memiliki field `users_id`, padahal database membutuhkannya sebagai foreign key ke tabel users.

## Perubahan yang Dilakukan

### 1. Controller (app/Http/Controllers/GuruController.php)

**Method create():**
- Menambahkan filter `whereDoesntHave('guru')` untuk hanya menampilkan user yang belum memiliki data guru

**Method store():**
- Menambahkan validasi `users_id` yang required dan harus exists di tabel users

**Method update():**
- Menambahkan validasi `users_id` yang required dan harus exists di tabel users

### 2. View Create (resources/views/guru/create.blade.php)

**Penambahan:**
- Field dropdown `users_id` untuk memilih user dengan role Guru atau Wali Kelas
- Menampilkan nama dan email user di dropdown
- Helper text untuk panduan user
- Placeholder pada setiap input field
- Default value "aktif" pada status

### 3. View Edit (resources/views/guru/edit.blade.php)

**Penambahan:**
- Field dropdown `users_id` untuk memilih user dengan role Guru atau Wali Kelas
- Pre-filled data users_id dari guru yang sedang diedit
- Menampilkan nama dan email user di dropdown
- Helper text untuk panduan user
- Placeholder pada setiap input field

### 4. View Index (resources/views/guru/index.blade.php)

**Perbaikan:**
- Menambahkan kolom Email untuk menampilkan email user
- Menggunakan `nama_guru` dari tabel guru (bukan dari user)
- Memperbaiki colspan dari 6 menjadi 7
- Badge status yang lebih jelas

## Struktur Database Guru

Tabel: `gurus`
- `id` (PK)
- `users_id` (FK ke users) - **REQUIRED**
- `nip` (unique)
- `nama_guru`
- `bidang_studi` (nullable)
- `status` (enum: aktif, tidak_aktif)
- `timestamps`

## Relasi

- **Guru belongsTo User**: Setiap guru terhubung dengan 1 user account
- User dengan role `guru` atau `wali_kelas` bisa dijadikan data guru
- Satu user hanya bisa memiliki 1 data guru (one-to-one)

## Fitur yang Sudah Diperbaiki

✅ Field users_id wajib diisi saat create/update
✅ Dropdown user hanya menampilkan user dengan role guru/wali_kelas
✅ User yang sudah memiliki data guru tidak muncul di dropdown create
✅ Tampilan index dengan kolom email
✅ Validasi yang ketat
✅ Error handling yang baik
✅ Styling konsisten dengan AdminLTE

## Cara Penggunaan

### Menambah Guru Baru:
1. Pastikan sudah ada user dengan role `guru` atau `wali_kelas`
2. Buka menu Guru > Tambah Guru
3. Pilih user dari dropdown
4. Isi NIP, Nama Guru, Bidang Studi, dan Status
5. Klik Simpan

### Catatan Penting:
- User harus dibuat terlebih dahulu di menu Manage Users
- Satu user hanya bisa dijadikan 1 data guru
- NIP harus unique di seluruh sistem
- Status default adalah "aktif"

## Sesuai dengan Modul

Perbaikan ini mengikuti:
- **Modul 1**: Struktur Tabel Database - GURU (Halaman 11)
- **Modul 2**: Ruang Lingkup Sistem - Manajemen Data Master (Halaman 7)

Field `users_id` diperlukan untuk menghubungkan data guru dengan akun login sistem.
