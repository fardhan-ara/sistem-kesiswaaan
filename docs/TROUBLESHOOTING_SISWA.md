# Troubleshooting Data Master Siswa

## Masalah yang Diperbaiki

### 1. Akses ke Data Master Siswa
**Masalah**: User tidak dapat mengakses halaman data master siswa
**Penyebab**: Middleware role membatasi akses hanya untuk role `admin` dan `kesiswaan`
**Solusi**: 
- Pastikan user yang login memiliki role `admin` atau `kesiswaan`
- Middleware sekarang memberikan pesan error yang jelas jika akses ditolak

### 2. CRUD Tidak Berfungsi
**Masalah**: Operasi Create, Read, Update, Delete tidak berjalan
**Penyebab**: Error handling kurang baik, tidak ada logging
**Solusi**:
- Menambahkan try-catch di semua method controller
- Menambahkan logging untuk debugging
- Menambahkan alert success/error di view

## Cara Mengecek Akses

### 1. Cek Role User yang Login
Akses URL: `http://localhost:8000/debug-siswa`

Response akan menampilkan:
```json
{
  "user": "Nama User",
  "role": "admin",
  "can_access_siswa": true,
  "siswa_count": 10
}
```

### 2. Cek Log Laravel
Buka file: `storage/logs/laravel.log`

Cari log dengan keyword:
- `RoleMiddleware check` - untuk melihat pengecekan role
- `Storing siswa data` - untuk melihat proses create
- `Updating siswa` - untuk melihat proses update
- `Error` - untuk melihat error yang terjadi

## Cara Mengatasi Masalah Akses

### Jika User Tidak Bisa Akses (Role Salah)

1. **Login sebagai Admin**
   - Email: admin@test.com
   - Password: password

2. **Atau Update Role User via Database**
   ```sql
   -- Cek role user
   SELECT id, nama, email, role FROM users;
   
   -- Update role user menjadi admin
   UPDATE users SET role = 'admin' WHERE email = 'email@user.com';
   
   -- Atau update menjadi kesiswaan
   UPDATE users SET role = 'kesiswaan' WHERE email = 'email@user.com';
   ```

3. **Atau via Tinker**
   ```bash
   php artisan tinker
   ```
   ```php
   $user = App\Models\User::where('email', 'email@user.com')->first();
   $user->role = 'admin';
   $user->save();
   ```

## Testing CRUD Siswa

### 1. Test Create (Tambah Siswa)
1. Login sebagai admin/kesiswaan
2. Akses: `http://localhost:8000/siswa`
3. Klik tombol "Tambah Siswa"
4. Isi form:
   - NIS: 12345
   - Nama Siswa: Test Siswa
   - Jenis Kelamin: Laki-laki
   - Kelas: Pilih dari dropdown
   - Tahun Ajaran: Pilih dari dropdown
5. Klik "Simpan"
6. Cek apakah muncul alert success

### 2. Test Read (Lihat Data)
1. Akses: `http://localhost:8000/siswa`
2. Pastikan data siswa muncul di tabel
3. Cek pagination jika data lebih dari 20

### 3. Test Update (Edit Siswa)
1. Di halaman data siswa, klik tombol edit (icon pensil)
2. Ubah data (misal: nama siswa)
3. Klik "Update"
4. Cek apakah muncul alert success dan data berubah

### 4. Test Delete (Hapus Siswa)
1. Di halaman data siswa, klik tombol hapus (icon trash)
2. Konfirmasi hapus
3. Cek apakah muncul alert success dan data terhapus

**Catatan**: Siswa yang memiliki pelanggaran atau prestasi tidak bisa dihapus

## Perintah Artisan Berguna

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cek routes
php artisan route:list | grep siswa

# Cek log real-time
tail -f storage/logs/laravel.log

# Reset database (HATI-HATI: akan menghapus semua data)
php artisan migrate:fresh --seed
```

## Struktur Route Siswa

```
GET    /siswa              -> siswa.index    (Lihat daftar)
GET    /siswa/create       -> siswa.create   (Form tambah)
POST   /siswa              -> siswa.store    (Simpan data)
GET    /siswa/{id}         -> siswa.show     (Detail)
GET    /siswa/{id}/edit    -> siswa.edit     (Form edit)
PUT    /siswa/{id}         -> siswa.update   (Update data)
DELETE /siswa/{id}         -> siswa.destroy  (Hapus data)
```

## Validasi Form

### Create/Store
- NIS: required, unique
- Nama Siswa: required, max 255 karakter
- Jenis Kelamin: required, L atau P
- Kelas ID: required, harus ada di tabel kelas
- Tahun Ajaran ID: required, harus ada di tabel tahun_ajarans

### Update
- Sama seperti create, tapi NIS unique kecuali untuk siswa yang sedang diedit

## Kontak Support

Jika masih ada masalah:
1. Cek log di `storage/logs/laravel.log`
2. Akses `/debug-siswa` untuk cek akses
3. Pastikan database terkoneksi dengan baik
4. Pastikan semua migration sudah dijalankan
