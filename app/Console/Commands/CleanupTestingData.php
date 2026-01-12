<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupTestingData extends Command
{
    protected $signature = 'cleanup:testing';
    protected $description = 'Hapus data testing (guru, siswa, ortu yang pending)';

    public function handle()
    {
        $this->info('Menghapus data testing...');
        
        $users = DB::table('users')->whereIn('role', ['guru', 'siswa', 'ortu'])->where('status', 'pending')->delete();
        $this->info("Dihapus {$users} users pending");
        
        $gurus = DB::table('gurus')->where('nip', 'like', 'TEMP-%')->delete();
        $this->info("Dihapus {$gurus} guru temporary");
        
        $this->info('Selesai!');
        return 0;
    }
}
