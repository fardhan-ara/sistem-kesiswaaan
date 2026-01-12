# TEST REDIRECT SETIAP ROLE

## Login dengan user berikut untuk test:

### 1. ADMIN
- Email: `admin@gmail.com`
- Password: `admin123`
- Expected: Dashboard Admin (view: dashboard.admin)

### 2. KESISWAAN
- Email: `darma@test.com`
- Password: Reset dulu dengan command:
```bash
php artisan tinker --execute="$u = App\Models\User::where('email', 'darma@test.com')->first(); $u->password = Hash::make('kesiswaan123'); $u->save(); echo 'Password reset ke: kesiswaan123';"
```
- Expected: Dashboard Kesiswaan (view: dashboard.admin)

### 3. GURU
- Email: `susilo@gmail.com`
- Password: Reset dulu dengan command:
```bash
php artisan tinker --execute="$u = App\Models\User::where('email', 'susilo@gmail.com')->first(); $u->password = Hash::make('guru123'); $u->save(); echo 'Password reset ke: guru123';"
```
- Expected: Dashboard Guru (view: dashboard.guru)

### 4. SISWA
- Email: `fardhan@gmail.com`
- Password: Reset dulu dengan command:
```bash
php artisan tinker --execute="$u = App\Models\User::where('email', 'fardhan@gmail.com')->first(); $u->password = Hash::make('siswa123'); $u->save(); echo 'Password reset ke: siswa123';"
```
- Expected: Dashboard Siswa (view: dashboard.siswa)

### 5. ORTU
- Email: `dadang@gmail.com`
- Password: Reset dulu dengan command:
```bash
php artisan tinker --execute="$u = App\Models\User::where('email', 'dadang@gmail.com')->first(); $u->password = Hash::make('ortu123'); $u->save(); echo 'Password reset ke: ortu123';"
```
- Expected: Dashboard Ortu (view: dashboard.ortu)

## CARA TEST:
1. Logout dari akun sekarang
2. Login dengan salah satu user di atas
3. Cek apakah redirect ke dashboard yang BENAR sesuai role
4. Jika masih redirect ke admin dashboard, beritahu saya ROLE mana yang bermasalah
