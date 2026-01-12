<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;

class CheckSiswaAccess extends Command
{
    protected $signature = 'siswa:check-access {email?}';
    protected $description = 'Check user access to siswa module and display statistics';

    public function handle()
    {
        $this->info('=== SISTEM KESISWAAN - CHECK SISWA ACCESS ===');
        $this->newLine();

        // Check specific user or all users
        $email = $this->argument('email');
        
        if ($email) {
            $this->checkUserAccess($email);
        } else {
            $this->displayStatistics();
        }
    }

    private function checkUserAccess($email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User dengan email '{$email}' tidak ditemukan!");
            return;
        }

        $this->info("User Information:");
        $this->table(
            ['Field', 'Value'],
            [
                ['Nama', $user->nama],
                ['Email', $user->email],
                ['Role', $user->role],
                ['Email Verified', $user->email_verified_at ? 'Yes' : 'No'],
                ['Can Access Siswa', in_array($user->role, ['admin', 'kesiswaan']) ? 'YES ✓' : 'NO ✗'],
            ]
        );

        if (!in_array($user->role, ['admin', 'kesiswaan'])) {
            $this->newLine();
            $this->warn('User ini TIDAK BISA mengakses Data Master Siswa!');
            $this->info('Role yang dibutuhkan: admin atau kesiswaan');
            $this->newLine();
            
            if ($this->confirm('Apakah Anda ingin mengubah role user ini menjadi admin?')) {
                $user->role = 'admin';
                $user->save();
                $this->info('✓ Role berhasil diubah menjadi admin!');
            }
        } else {
            $this->newLine();
            $this->info('✓ User ini BISA mengakses Data Master Siswa!');
        }
    }

    private function displayStatistics()
    {
        // User statistics
        $this->info('1. USER STATISTICS');
        $userStats = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->get();
        
        $this->table(['Role', 'Count'], $userStats->map(function($stat) {
            return [$stat->role, $stat->count];
        }));

        // Users with access to siswa
        $this->newLine();
        $this->info('2. USERS WITH SISWA ACCESS (admin & kesiswaan)');
        $usersWithAccess = User::whereIn('role', ['admin', 'kesiswaan'])->get();
        
        if ($usersWithAccess->count() > 0) {
            $this->table(
                ['Nama', 'Email', 'Role', 'Verified'],
                $usersWithAccess->map(function($user) {
                    return [
                        $user->nama,
                        $user->email,
                        $user->role,
                        $user->email_verified_at ? 'Yes' : 'No'
                    ];
                })
            );
        } else {
            $this->warn('Tidak ada user dengan akses ke Data Master Siswa!');
        }

        // Siswa statistics
        $this->newLine();
        $this->info('3. SISWA STATISTICS');
        $siswaCount = Siswa::count();
        $siswaLaki = Siswa::where('jenis_kelamin', 'L')->count();
        $siswaPerempuan = Siswa::where('jenis_kelamin', 'P')->count();
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Siswa', $siswaCount],
                ['Laki-laki', $siswaLaki],
                ['Perempuan', $siswaPerempuan],
            ]
        );

        // Kelas statistics
        $this->newLine();
        $this->info('4. KELAS STATISTICS');
        $kelasCount = Kelas::count();
        $this->info("Total Kelas: {$kelasCount}");

        // Tahun Ajaran
        $this->newLine();
        $this->info('5. TAHUN AJARAN AKTIF');
        $tahunAjaranAktif = TahunAjaran::where('status_aktif', 'aktif')->first();
        
        if ($tahunAjaranAktif) {
            $this->info("✓ {$tahunAjaranAktif->tahun_ajaran} - {$tahunAjaranAktif->semester}");
        } else {
            $this->warn('Tidak ada tahun ajaran aktif!');
        }

        // Recommendations
        $this->newLine();
        $this->info('=== RECOMMENDATIONS ===');
        
        if ($usersWithAccess->count() == 0) {
            $this->warn('⚠ Tidak ada user dengan akses ke Data Master Siswa!');
            $this->info('  Jalankan: php artisan siswa:check-access <email> untuk mengubah role user');
        }
        
        if ($siswaCount == 0) {
            $this->warn('⚠ Belum ada data siswa!');
            $this->info('  Tambahkan siswa melalui: http://localhost:8000/siswa/create');
        }
        
        if ($kelasCount == 0) {
            $this->warn('⚠ Belum ada data kelas!');
            $this->info('  Tambahkan kelas terlebih dahulu sebelum menambah siswa');
        }
        
        if (!$tahunAjaranAktif) {
            $this->warn('⚠ Belum ada tahun ajaran aktif!');
            $this->info('  Aktifkan tahun ajaran terlebih dahulu');
        }

        $this->newLine();
        $this->info('Untuk cek akses user tertentu, jalankan:');
        $this->comment('php artisan siswa:check-access <email>');
    }
}
