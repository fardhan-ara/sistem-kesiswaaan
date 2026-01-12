# SISTEM NOTIFIKASI INTERNAL - DOKUMENTASI LENGKAP

## 📋 ALUR LOGIKA SISTEM NOTIFIKASI

### 1. ADMIN MENGIRIM NOTIFIKASI
```
Admin → Form Kirim Notifikasi → Pilih Role Target → Submit
↓
NotificationController@store()
↓
Validasi Input (title, message, type, target_role)
↓
Query User berdasarkan role: User::where('role', $target_role)->get()
↓
Kirim notifikasi: Notification::send($users, new SystemNotification(...))
↓
Data disimpan ke tabel 'notifications' (JSON format)
```

### 2. USER MELIHAT NOTIFIKASI
```
User Login → Navbar Badge menampilkan jumlah unread
↓
User klik icon bell → Dropdown menampilkan 5 notifikasi terbaru
↓
User klik "Lihat Semua" → Halaman notifications.index
↓
Query: Auth::user()->notifications()->paginate(15)
↓
Tampilkan list notifikasi dengan status read/unread
```

### 3. TANDAI SEBAGAI DIBACA
```
User klik tombol "Tandai Dibaca" atau klik notifikasi
↓
NotificationController@markAsRead($id)
↓
$notification->markAsRead() → Update kolom 'read_at' = now()
↓
Redirect ke action_url (jika ada) atau kembali
```

### 4. QUERY ELOQUENT YANG DIGUNAKAN

#### Ambil semua notifikasi user:
```php
Auth::user()->notifications()->paginate(15);
```

#### Ambil notifikasi belum dibaca:
```php
Auth::user()->unreadNotifications()->get();
```

#### Hitung notifikasi belum dibaca:
```php
Auth::user()->unreadNotifications()->count();
```

#### Tandai satu notifikasi dibaca:
```php
$notification = Auth::user()->notifications()->find($id);
$notification->markAsRead();
```

#### Tandai semua notifikasi dibaca:
```php
Auth::user()->unreadNotifications->markAsRead();
```

#### Hapus notifikasi:
```php
Auth::user()->notifications()->find($id)->delete();
```

## 🗄️ STRUKTUR DATABASE

### Tabel: notifications
```
- id (uuid, primary key)
- type (string) → Class notification
- notifiable_type (string) → "App\Models\User"
- notifiable_id (bigint) → user_id
- data (text/json) → {title, message, type, action_url, action_text}
- read_at (timestamp, nullable) → null = belum dibaca
- created_at (timestamp)
- updated_at (timestamp)
```

### Contoh Data JSON di kolom 'data':
```json
{
    "title": "Pengumuman Penting",
    "message": "Besok ada rapat guru jam 10.00",
    "type": "warning",
    "action_url": "http://localhost/rapat/123",
    "action_text": "Lihat Detail",
    "created_at": "2025-01-07 10:30:00"
}
```

## 🔧 CARA PENGGUNAAN

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Pastikan User Model menggunakan Notifiable
```php
// app/Models/User.php
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
}
```

### 3. Tambahkan Routes
Copy isi file `routes/notifications_routes.php` ke `routes/web.php`

### 4. Tambahkan Dropdown di Navbar
Include component di `layouts/app.blade.php`:
```blade
@include('components.notification-dropdown')
```

### 5. Kirim Notifikasi Manual (via Tinker)
```php
php artisan tinker

use App\Models\User;
use App\Notifications\SystemNotification;

$user = User::find(1);
$user->notify(new SystemNotification(
    'Judul', 
    'Pesan', 
    'info', 
    'http://example.com', 
    'Lihat'
));
```

## 🎯 FITUR YANG TERSEDIA

✅ Admin kirim notifikasi ke user berdasarkan role
✅ Notifikasi disimpan di database
✅ Status dibaca/belum dibaca
✅ Badge counter di navbar
✅ Dropdown 5 notifikasi terbaru
✅ Halaman daftar semua notifikasi
✅ Tandai satu/semua sebagai dibaca
✅ Hapus notifikasi
✅ Action button dengan custom URL
✅ 4 tipe notifikasi (info, success, warning, danger)
✅ Pagination
✅ Timestamp dengan format "diffForHumans"

## 📱 API ENDPOINTS (Optional)

```php
GET  /api/notifications/unread-count  → {count: 5}
GET  /api/notifications/latest        → [...]
```

Untuk AJAX real-time update badge counter.

## 🚀 PENGEMBANGAN LEBIH LANJUT

1. **Real-time dengan Pusher/Laravel Echo**
2. **Email notification** (tambah channel 'mail')
3. **SMS notification** (tambah channel custom)
4. **Notifikasi otomatis** (event listener)
5. **Filter notifikasi** (by type, date)
6. **Export notifikasi** (PDF/Excel)

---
Sistem notifikasi siap digunakan! 🎉
