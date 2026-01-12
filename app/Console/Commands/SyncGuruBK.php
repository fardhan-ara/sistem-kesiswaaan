<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Guru;

class SyncGuruBK extends Command
{
    protected $signature = 'guru:sync-bk';
    protected $description = 'Sinkronisasi data guru untuk user dengan role BK';

    public function handle()
    {
        $this->info('Memulai sinkronisasi data guru BK...');
        
        $usersBK = User::where('role', 'bk')
            ->orWhereJsonContains('additional_roles', 'bk')
            ->get();
        
        $created = 0;
        $skipped = 0;
        
        foreach ($usersBK as $user) {
            $existingGuru = Guru::where('users_id', $user->id)->first();
            
            if ($existingGuru) {
                $this->line("User {$user->nama} sudah punya data guru (ID: {$existingGuru->id})");
                $skipped++;
                continue;
            }
            
            Guru::create([
                'users_id' => $user->id,
                'nip' => 'AUTO-' . $user->id,
                'nama_guru' => $user->nama,
                'bidang_studi' => 'Bimbingan Konseling (BK)',
                'status' => 'aktif'
            ]);
            
            $this->info("✓ Data guru BK dibuat untuk: {$user->nama}");
            $created++;
        }
        
        $this->newLine();
        $this->info("Selesai! Dibuat: {$created}, Dilewati: {$skipped}");
        
        return Command::SUCCESS;
    }
}
