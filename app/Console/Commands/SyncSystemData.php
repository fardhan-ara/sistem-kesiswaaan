<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;

class SyncSystemData extends Command
{
    protected $signature = 'system:sync';
    protected $description = 'Synchronize all system data relationships';

    public function handle()
    {
        $this->info('🔄 Starting system data synchronization...');
        
        // 1. Fix orphaned guru users
        $this->info('1️⃣ Fixing orphaned guru users...');
        $this->fixOrphanedGuruUsers();
        
        // 2. Assign wali kelas
        $this->info('2️⃣ Assigning wali kelas to classes...');
        $this->assignWaliKelas();
        
        // 3. Verify all relationships
        $this->info('3️⃣ Verifying relationships...');
        $this->verifyRelationships();
        
        $this->info('✅ Synchronization complete!');
    }

    private function fixOrphanedGuruUsers()
    {
        $orphanUsers = User::whereIn('role', ['guru', 'bk', 'wali_kelas'])
            ->whereDoesntHave('guru')
            ->get();

        foreach ($orphanUsers as $user) {
            $nip = 'AUTO-' . $user->id . '-' . date('Ymd');
            
            Guru::create([
                'users_id' => $user->id,
                'nip' => $nip,
                'nama_guru' => $user->nama,
                'bidang_studi' => $user->role === 'bk' ? 'Bimbingan Konseling (BK)' : 'Umum',
                'status' => 'aktif',
                'status_approval' => 'approved'
            ]);
            
            $this->info("   ✓ Created guru record for: {$user->email}");
        }
    }

    private function assignWaliKelas()
    {
        $gurus = Guru::where('status', 'aktif')->get();
        $kelas = Kelas::whereNull('wali_kelas_id')->get();
        
        $guruIndex = 0;
        foreach ($kelas as $k) {
            if ($guruIndex >= $gurus->count()) {
                $guruIndex = 0;
            }
            
            $k->wali_kelas_id = $gurus[$guruIndex]->id;
            $k->save();
            
            // Update user to have wali_kelas flag
            $guru = $gurus[$guruIndex];
            if ($guru->user) {
                $guru->user->is_wali_kelas = true;
                $guru->user->save();
            }
            
            $this->info("   ✓ Assigned {$guru->nama_guru} to {$k->nama_kelas}");
            $guruIndex++;
        }
    }

    private function verifyRelationships()
    {
        $issues = [];
        
        // Check guru-user sync
        $gurusWithoutUser = Guru::whereDoesntHave('user')->count();
        if ($gurusWithoutUser > 0) {
            $issues[] = "⚠️  {$gurusWithoutUser} guru records without user";
        }
        
        // Check siswa-user sync
        $siswasWithoutUser = Siswa::whereDoesntHave('user')->count();
        if ($siswasWithoutUser > 0) {
            $issues[] = "⚠️  {$siswasWithoutUser} siswa records without user";
        }
        
        // Check kelas without wali
        $kelasWithoutWali = Kelas::whereNull('wali_kelas_id')->count();
        if ($kelasWithoutWali > 0) {
            $issues[] = "⚠️  {$kelasWithoutWali} classes without wali kelas";
        }
        
        if (empty($issues)) {
            $this->info('   ✓ All relationships verified successfully!');
        } else {
            foreach ($issues as $issue) {
                $this->warn($issue);
            }
        }
        
        // Display summary
        $this->info("\n📊 System Summary:");
        $this->table(
            ['Entity', 'Count'],
            [
                ['Users', User::count()],
                ['Guru', Guru::count()],
                ['Siswa', Siswa::count()],
                ['Kelas', Kelas::count()],
                ['Wali Kelas Assigned', Kelas::whereNotNull('wali_kelas_id')->count()],
            ]
        );
    }
}
