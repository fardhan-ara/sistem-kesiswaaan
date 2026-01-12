<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\JenisPelanggaran;
use App\Models\JenisPrestasi;

class SystemHealthCheck extends Command
{
    protected $signature = 'system:health';
    protected $description = 'Check system health and data integrity';

    public function handle()
    {
        $this->info('🏥 System Health Check');
        $this->newLine();
        
        $issues = 0;
        
        // 1. Check Active Tahun Ajaran
        $this->info('1️⃣ Checking Tahun Ajaran...');
        $activeTa = TahunAjaran::where('status_aktif', 'aktif')->first();
        if (!$activeTa) {
            $this->error('   ❌ No active tahun ajaran!');
            $issues++;
        } else {
            $this->info("   ✓ Active: {$activeTa->tahun_ajaran} ({$activeTa->semester})");
        }
        
        // 2. Check User-Guru Sync
        $this->info('2️⃣ Checking User-Guru Synchronization...');
        $orphanGuru = User::whereIn('role', ['guru', 'bk', 'wali_kelas'])
            ->whereDoesntHave('guru')->count();
        if ($orphanGuru > 0) {
            $this->error("   ❌ {$orphanGuru} guru users without guru record");
            $issues++;
        } else {
            $this->info('   ✓ All guru users have guru records');
        }
        
        // 3. Check User-Siswa Sync
        $this->info('3️⃣ Checking User-Siswa Synchronization...');
        $orphanSiswa = User::where('role', 'siswa')
            ->whereDoesntHave('siswa')->count();
        if ($orphanSiswa > 0) {
            $this->error("   ❌ {$orphanSiswa} siswa users without siswa record");
            $issues++;
        } else {
            $this->info('   ✓ All siswa users have siswa records');
        }
        
        // 4. Check Wali Kelas Assignment
        $this->info('4️⃣ Checking Wali Kelas Assignments...');
        $kelasWithoutWali = Kelas::whereNull('wali_kelas_id')->count();
        if ($kelasWithoutWali > 0) {
            $this->error("   ❌ {$kelasWithoutWali} classes without wali kelas");
            $issues++;
        } else {
            $this->info('   ✓ All classes have wali kelas');
        }
        
        // 5. Check Siswa Tahun Ajaran
        $this->info('5️⃣ Checking Siswa Tahun Ajaran...');
        $siswaWithoutTa = Siswa::whereNull('tahun_ajaran_id')->count();
        if ($siswaWithoutTa > 0) {
            $this->error("   ❌ {$siswaWithoutTa} siswa without tahun ajaran");
            $issues++;
        } else {
            $this->info('   ✓ All siswa have tahun ajaran');
        }
        
        // 6. Check Master Data
        $this->info('6️⃣ Checking Master Data...');
        $jenisPelanggaran = JenisPelanggaran::count();
        $jenisPrestasi = JenisPrestasi::count();
        
        if ($jenisPelanggaran === 0) {
            $this->error('   ❌ No jenis pelanggaran data');
            $issues++;
        } else {
            $this->info("   ✓ Jenis Pelanggaran: {$jenisPelanggaran}");
        }
        
        if ($jenisPrestasi === 0) {
            $this->warn('   ⚠️  No jenis prestasi data');
        } else {
            $this->info("   ✓ Jenis Prestasi: {$jenisPrestasi}");
        }
        
        // 7. Check Admin User
        $this->info('7️⃣ Checking Admin User...');
        $admin = User::where('email', 'admin@test.com')->first();
        if (!$admin) {
            $this->error('   ❌ Admin user not found!');
            $issues++;
        } else {
            $this->info("   ✓ Admin exists: {$admin->email}");
        }
        
        // Summary
        $this->newLine();
        $this->info('📊 System Summary:');
        $this->table(
            ['Entity', 'Count', 'Status'],
            [
                ['Users', User::count(), '✓'],
                ['Guru', Guru::count(), '✓'],
                ['Siswa', Siswa::count(), '✓'],
                ['Kelas', Kelas::count(), '✓'],
                ['Tahun Ajaran', TahunAjaran::count(), '✓'],
                ['Jenis Pelanggaran', $jenisPelanggaran, $jenisPelanggaran > 0 ? '✓' : '❌'],
                ['Jenis Prestasi', $jenisPrestasi, $jenisPrestasi > 0 ? '✓' : '⚠️'],
            ]
        );
        
        $this->newLine();
        if ($issues === 0) {
            $this->info('✅ System is healthy! No issues found.');
            return 0;
        } else {
            $this->error("❌ Found {$issues} issue(s). Run 'php artisan system:sync' to fix.");
            return 1;
        }
    }
}
