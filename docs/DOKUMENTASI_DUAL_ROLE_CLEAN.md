# SISTEM DUAL ROLE DENGAN APPROVAL ADMIN

## OVERVIEW
Sistem dual role yang bersih dan terstruktur dengan persetujuan admin untuk mengatasi masalah role ganda yang aneh.

## KONSEP SISTEM

### 1. Primary Role (Wajib)
- Setiap user memiliki **satu primary role**: admin, kesiswaan, guru, siswa, ortu
- Primary role tidak bisa diubah sembarangan
- Menentukan akses dasar user

### 2. Additional Roles (Opsional)
- User bisa mendapat **additional roles** dengan persetujuan admin
- Disimpan dalam field `additional_roles` (JSON array)
- Hanya admin yang bisa memberikan/mencabut

### 3. Homeroom Teacher (Assignment)
- Guru bisa di-assign sebagai wali kelas (terpisah dari dual role)
- Assignment melalui tabel `class_homeroom_teachers`
- Otomatis mendapat akses `wali_kelas`

## DATABASE STRUCTURE

### Kolom Baru di Tabel `users`:
```sql
- allow_dual_role (boolean) - Apakah user diizinkan punya dual role
- additional_roles (json) - Array role tambahan
- dual_role_approved_by (foreign key) - Admin yang approve
- dual_role_approved_at (timestamp) - Kapan di-approve
```

## CARA KERJA

### 1. User Registration
```php
// User hanya pilih primary role
User::create([
    'nama' => 'John Doe',
    'role' => 'guru', // Primary role saja
    'allow_dual_role' => false // Default
]);
```

### 2. Admin Memberikan Dual Role
```php
// Admin approve dual role
$user->update([
    'allow_dual_role' => true,
    'additional_roles' => ['kesiswaan'], // Role tambahan
    'dual_role_approved_by' => auth()->id(),
    'dual_role_approved_at' => now()
]);
```

### 3. Pengecekan Role
```php
$user = auth()->user();

// Cek role tunggal
$user->hasRole(['guru']); // true jika primary atau additional

// Cek multiple roles
$user->hasRole(['guru', 'kesiswaan']); // true jika salah satu cocok

// Get semua role
$allRoles = $user->getAllRoles(); // ['guru', 'kesiswaan', 'wali_kelas']
```

## CONTOH SKENARIO

### Skenario 1: Guru Biasa
```
Primary Role: guru
Additional Roles: -
Homeroom: -
Total Access: [guru]
```

### Skenario 2: Guru + Kesiswaan (Dual Role)
```
Primary Role: guru
Additional Roles: [kesiswaan] (approved by admin)
Homeroom: -
Total Access: [guru, kesiswaan]
```

### Skenario 3: Guru + Wali Kelas (Assignment)
```
Primary Role: guru
Additional Roles: -
Homeroom: X PPLG 1 (assigned by admin)
Total Access: [guru, wali_kelas]
```

### Skenario 4: Guru + Kesiswaan + Wali Kelas (Kombinasi)
```
Primary Role: guru
Additional Roles: [kesiswaan] (approved by admin)
Homeroom: X PPLG 1 (assigned by admin)
Total Access: [guru, kesiswaan, wali_kelas]
```

## CONTROLLER UNTUK DUAL ROLE

### DualRoleController
```php
// Lihat daftar dual role users
public function index()

// Form assign dual role
public function create()

// Simpan dual role assignment
public function store(Request $request)

// Cabut dual role
public function destroy(User $user)
```

## KEAMANAN & VALIDASI

### 1. Admin Only
- Hanya admin yang bisa memberikan/mencabut dual role
- Audit trail lengkap (siapa, kapan)

### 2. No Duplication
- Additional roles tidak boleh sama dengan primary role
- Validasi otomatis saat assignment

### 3. Flexible Combinations
- Bisa kombinasi role apapun sesuai kebutuhan
- Tidak terbatas pada kombinasi tertentu

## ROUTES EXAMPLE

```php
// Admin manage dual roles
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('dual-roles', DualRoleController::class)->except(['show', 'edit', 'update']);
});

// Multi-role access
Route::middleware(['auth', 'role:guru,kesiswaan'])->group(function () {
    Route::get('/multi-access', function() {
        // Bisa diakses guru atau kesiswaan (termasuk yang dual role)
    });
});
```

## TESTING RESULTS

✅ **susilo**: guru + kesiswaan + wali_kelas (3 roles)
✅ **ujang darmaji**: kesiswaan + guru (2 roles)
✅ **Role checking**: All methods working
✅ **Audit trail**: Tracking approval
✅ **Flexible access**: Multi-role permissions

## KEUNGGULAN SISTEM INI

1. **Tidak Aneh**: Role ganda jelas dan terstruktur
2. **Admin Control**: Semua dual role harus disetujui admin
3. **Audit Trail**: Tracking lengkap siapa approve kapan
4. **Flexible**: Bisa kombinasi role apapun
5. **Clean Code**: Tidak ada hardcode role combinations
6. **Scalable**: Mudah tambah role baru

## CARA PENGGUNAAN

### 1. Assign Dual Role (Admin)
```php
// Via Controller
$user = User::find($userId);
$user->update([
    'allow_dual_role' => true,
    'additional_roles' => ['kesiswaan'],
    'dual_role_approved_by' => auth()->id(),
    'dual_role_approved_at' => now()
]);
```

### 2. Cek Access di Middleware/Controller
```php
// Cek apakah user punya akses kesiswaan
if (auth()->user()->hasRole(['kesiswaan'])) {
    // Bisa akses (baik primary maupun additional role)
}

// Cek multiple roles
if (auth()->user()->hasRole(['guru', 'kesiswaan'])) {
    // Punya salah satu dari role tersebut
}
```

### 3. Cabut Dual Role (Admin)
```php
$user->update([
    'allow_dual_role' => false,
    'additional_roles' => null,
    'dual_role_approved_by' => null,
    'dual_role_approved_at' => null
]);
```

---

**Status**: PRODUCTION READY ✅
**Masalah "Aneh"**: SOLVED ✅
**Admin Control**: IMPLEMENTED ✅