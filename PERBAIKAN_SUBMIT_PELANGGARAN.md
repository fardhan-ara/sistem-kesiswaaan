# 🔧 Perbaikan: Tidak Bisa Submit Pelanggaran

## 🔍 Kemungkinan Penyebab

Berdasarkan analisis kode, ada beberapa kemungkinan penyebab:

### 1. **Masalah Role/Permission**
User yang login tidak memiliki akses untuk submit pelanggaran.

### 2. **Data Guru Tidak Terkoneksi**
User guru tidak terhubung dengan data di tabel `gurus`.

### 3. **Data Master Kosong**
Tidak ada data siswa, guru, atau jenis pelanggaran.

### 4. **JavaScript Error**
Error di browser yang mencegah form submit.

### 5. **CSRF Token Expired**
Token keamanan Laravel sudah kadaluarsa.

---

## ✅ Solusi Cepat

### **Langkah 1: Cek Role User**

```bash
php artisan tinker
```

```php
// Cek user yang sedang login
$user = User::where('email', 'EMAIL_ANDA')->first();
echo "Role: " . $user->role;
echo "\nIs Wali Kelas: " . $user->is_wali_kelas;

// Role yang bisa submit: admin, kesiswaan, guru, wali_kelas, bk
```

**Perbaikan jika role salah:**
```php
$user->role = 'guru'; // atau admin, kesiswaan, wali_kelas, bk
$user->save();
```

---

### **Langkah 2: Cek Koneksi User dengan Data Guru**

```bash
php artisan tinker
```

```php
$user = User::where('email', 'EMAIL_ANDA')->first();
$guru = Guru::where('users_id', $user->id)->first();

if (!$guru) {
    echo "MASALAH: User tidak terhubung dengan data guru!";
} else {
    echo "OK: Guru ID = " . $guru->id;
}
```

**Perbaikan jika tidak ada koneksi:**
```php
// Buat data guru baru
Guru::create([
    'users_id' => $user->id,
    'nip' => 'AUTO-' . $user->id,
    'nama_guru' => $user->nama,
    'bidang_studi' => 'Umum',
    'status' => 'aktif'
]);
```

---

### **Langkah 3: Cek Data Master**

```bash
php artisan tinker
```

```php
// Cek jumlah data
echo "Siswa: " . Siswa::count();
echo "\nGuru: " . Guru::count();
echo "\nJenis Pelanggaran: " . JenisPelanggaran::count();
```

**Jika data kosong, jalankan seeder:**
```bash
php artisan db:seed
```

---

### **Langkah 4: Clear Cache**

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

### **Langkah 5: Cek Browser Console**

1. Buka halaman tambah pelanggaran
2. Tekan **F12** (Developer Tools)
3. Buka tab **Console**
4. Coba submit form
5. Lihat apakah ada error JavaScript

**Error umum:**
- `CSRF token mismatch` → Refresh halaman (Ctrl+F5)
- `jQuery is not defined` → Masalah library JavaScript
- `403 Forbidden` → Masalah permission

---

## 🛠️ Perbaikan Mendalam

### **A. Perbaiki Middleware Role**

Pastikan route pelanggaran bisa diakses:

**File:** `routes/web.php`

```php
// Pastikan ada route ini:
Route::middleware(['auth', 'role:admin,kesiswaan,guru,wali_kelas,bk'])->group(function () {
    Route::resource('pelanggaran', PelanggaranController::class);
});
```

---

### **B. Perbaiki Form Validation**

**File:** `app/Http/Controllers/PelanggaranController.php`

Pastikan validasi tidak terlalu ketat:

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'siswa_id' => 'required|exists:siswas,id',
        'guru_pencatat' => 'required|exists:gurus,id',
        'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggarans,id',
        'keterangan' => 'nullable|string',
        'tanggal_pelanggaran' => 'nullable|date'
    ]);
    
    // ... rest of code
}
```

---

### **C. Tambah Logging untuk Debug**

Tambahkan di method `store()`:

```php
public function store(Request $request)
{
    \Log::info('=== PELANGGARAN STORE CALLED ===');
    \Log::info('Request data:', $request->all());
    \Log::info('User:', ['id' => auth()->id(), 'role' => auth()->user()->role]);
    
    // ... rest of code
}
```

Lalu cek log:
```bash
tail -f storage/logs/laravel.log
```

---

### **D. Perbaiki Auto-Select Guru**

Jika guru harus otomatis terisi dengan user yang login:

**File:** `app/Http/Controllers/PelanggaranController.php`

```php
public function create()
{
    $user = auth()->user();
    $guruLogin = Guru::where('users_id', $user->id)->first();
    
    // ... rest of code
    
    return view('pelanggaran.create', compact(
        'siswas', 
        'gurus', 
        'jenisPelanggarans', 
        'kategoris',
        'guruLogin' // Kirim ke view
    ));
}
```

**File:** `resources/views/pelanggaran/create.blade.php`

```html
<select name="guru_pencatat" id="guru_pencatat" class="form-control" required>
    <option value="">Pilih Guru</option>
    @foreach($gurus as $guru)
        <option value="{{ $guru->id }}" 
            {{ isset($guruLogin) && $guruLogin->id == $guru->id ? 'selected' : '' }}>
            {{ $guru->nama_guru }}
        </option>
    @endforeach
</select>
```

---

## 🔍 Debugging Step-by-Step

### **1. Test Route**

```bash
php artisan route:list | grep pelanggaran
```

Pastikan ada:
- `POST /pelanggaran` → `PelanggaranController@store`

### **2. Test Permission**

```bash
php artisan tinker
```

```php
$user = User::find(1); // Ganti dengan ID user Anda
Auth::login($user);

// Test akses
$request = new \Illuminate\Http\Request();
$middleware = new \App\Http\Middleware\RoleMiddleware();

// Cek apakah bisa akses
try {
    $middleware->handle($request, function() { return 'OK'; }, 'admin', 'kesiswaan', 'guru');
    echo "Permission OK";
} catch (\Exception $e) {
    echo "Permission DENIED: " . $e->getMessage();
}
```

### **3. Test Database Connection**

```bash
php artisan tinker
```

```php
// Test insert manual
$pelanggaran = new Pelanggaran();
$pelanggaran->siswa_id = 1;
$pelanggaran->guru_pencatat = 1;
$pelanggaran->jenis_pelanggaran_id = 1;
$pelanggaran->tahun_ajaran_id = 1;
$pelanggaran->poin = 10;
$pelanggaran->tanggal_pelanggaran = now();
$pelanggaran->status_verifikasi = 'menunggu';
$pelanggaran->save();

echo "Test insert berhasil!";
```

---

## 📋 Checklist Troubleshooting

- [ ] User memiliki role yang benar (admin/kesiswaan/guru/wali_kelas/bk)
- [ ] User terhubung dengan data guru di tabel `gurus`
- [ ] Ada data siswa di database
- [ ] Ada data jenis pelanggaran di database
- [ ] Cache sudah di-clear
- [ ] Browser console tidak ada error
- [ ] CSRF token valid (refresh halaman)
- [ ] Route pelanggaran ada dan benar
- [ ] Middleware role sudah benar
- [ ] Database connection OK

---

## 🚨 Error Umum & Solusi

### **Error: "403 Forbidden"**
**Penyebab:** User tidak punya permission  
**Solusi:** Cek role user dan middleware

### **Error: "CSRF token mismatch"**
**Penyebab:** Token expired  
**Solusi:** Refresh halaman (Ctrl+F5)

### **Error: "The guru pencatat field is required"**
**Penyebab:** Dropdown guru kosong atau tidak terisi  
**Solusi:** Cek data guru dan auto-select

### **Error: "Call to a member function on null"**
**Penyebab:** User tidak terhubung dengan data guru  
**Solusi:** Buat koneksi user-guru

### **Form tidak submit (tidak ada response)**
**Penyebab:** JavaScript error  
**Solusi:** Cek browser console (F12)

---

## 💡 Tips Pencegahan

1. **Selalu cek role user** sebelum akses fitur
2. **Pastikan data master lengkap** (siswa, guru, jenis pelanggaran)
3. **Gunakan seeder** untuk data awal
4. **Test di browser berbeda** (Chrome, Firefox)
5. **Clear cache** setelah update kode
6. **Monitor log** saat development

---

## 📞 Bantuan Lebih Lanjut

Jika masih bermasalah, kirim informasi berikut:

1. **Role user yang login:**
   ```bash
   php artisan tinker
   User::where('email', 'EMAIL')->first()->role
   ```

2. **Error di browser console** (F12 → Console)

3. **Error di Laravel log:**
   ```bash
   tail -20 storage/logs/laravel.log
   ```

4. **Screenshot halaman form**

---

**Dibuat:** 2026-01-06  
**Versi:** 1.0  
**Status:** ✅ Siap Digunakan
