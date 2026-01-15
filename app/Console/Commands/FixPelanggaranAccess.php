<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\JenisPelanggaran;

class FixPelanggaranAccess extends Command
{
    protected $signature = 'pelanggaran:fix {email}';
    protected $description = 'Perbaiki akses submit pelanggaran untuk user';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info('');
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║     PERBAIKAN AKSES SUBMIT PELANGGARAN                     ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->info('');
        
        // Cari user
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ User dengan email '$email' tidak ditemukan!");
            return 1;
        }
        
        $this->info("✅ User ditemukan: {$user->nama}");
        $this->info("   Email: {$user->email}");
        $this->info("   Role: {$user->role}");
        $this->info('');
        
        $fixed = false;
        
        // Fix 1: Cek dan perbaiki role
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('1️⃣  Memeriksa Role...');
        
        $allowedRoles = ['admin', 'kesiswaan', 'guru', 'wali_kelas', 'bk'];
        
        if (!in_array($user->role, $allowedRoles)) {
            $this->warn("   ⚠️  Role '{$user->role}' tidak valid untuk submit pelanggaran");
            
            if ($this->confirm('   Ubah role menjadi "guru"?', true)) {
                $user->role = 'guru';
                $user->save();
                $this->info("   ✅ Role diubah menjadi 'guru'");
                $fixed = true;
            }
        } else {
            $this->info("   ✅ Role valid");
        }
        $this->info('');
        
        // Fix 2: Cek dan buat koneksi dengan guru
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('2️⃣  Memeriksa Koneksi User → Guru...');
        
        $guru = Guru::where('users_id', $user->id)->first();
        
        if (!$guru) {
            $this->warn("   ⚠️  User tidak terhubung dengan data guru");
            
            if ($this->confirm('   Buat data guru untuk user ini?', true)) {
                $guru = Guru::create([
                    'users_id' => $user->id,
                    'nip' => 'AUTO-' . $user->id,
                    'nama_guru' => $user->nama,
                    'bidang_studi' => 'Umum',
                    'status' => 'aktif'
                ]);
                
                $this->info("   ✅ Data guru berhasil dibuat");
                $this->info("      Guru ID: {$guru->id}");
                $this->info("      NIP: {$guru->nip}");
                $fixed = true;
            }
        } else {
            $this->info("   ✅ User terhubung dengan guru");
            $this->info("      Guru ID: {$guru->id}");
            $this->info("      NIP: {$guru->nip}");
        }
        $this->info('');
        
        // Fix 3: Cek data master
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('3️⃣  Memeriksa Data Master...');
        
        $jumlahSiswa = Siswa::count();
        $jumlahJenisPelanggaran = JenisPelanggaran::count();
        
        $this->info("   Siswa: $jumlahSiswa data");
        $this->info("   Jenis Pelanggaran: $jumlahJenisPelanggaran data");
        
        if ($jumlahSiswa == 0 || $jumlahJenisPelanggaran == 0) {
            $this->warn("   ⚠️  Data master tidak lengkap");
            
            if ($this->confirm('   Jalankan seeder untuk mengisi data?', true)) {
                $this->call('db:seed');
                $this->info("   ✅ Seeder berhasil dijalankan");
                $fixed = true;
            }
        } else {
            $this->info("   ✅ Data master lengkap");
        }
        $this->info('');
        
        // Fix 4: Clear cache
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('4️⃣  Membersihkan Cache...');
        
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');
        
        $this->info("   ✅ Cache berhasil dibersihkan");
        $this->info('');
        
        // Kesimpulan
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('📊 KESIMPULAN');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        if ($fixed) {
            $this->info('✅ Perbaikan berhasil dilakukan!');
            $this->info('');
            $this->info('Langkah selanjutnya:');
            $this->info('1. Login dengan email: ' . $email);
            $this->info('2. Buka halaman: /pelanggaran/create');
            $this->info('3. Coba submit pelanggaran');
            $this->info('');
            $this->info('Jika masih bermasalah:');
            $this->info('- Clear browser cache (Ctrl+Shift+Del)');
            $this->info('- Cek browser console (F12) untuk error');
            $this->info('- Lihat log: storage/logs/laravel.log');
        } else {
            $this->info('ℹ️  Tidak ada perbaikan yang dilakukan');
            $this->info('   Sistem sudah dalam kondisi baik');
        }
        
        $this->info('');
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║                  PERBAIKAN SELESAI                         ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->info('');
        
        return 0;
    }
}
