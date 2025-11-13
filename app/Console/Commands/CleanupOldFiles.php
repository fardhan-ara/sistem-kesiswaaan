<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CleanupOldFiles extends Command
{
    protected $signature = 'cleanup:old-files';
    protected $description = 'Hapus log lama >30 hari dan backup lama >7 hari';

    public function handle()
    {
        $this->info('Memulai cleanup...');
        
        $deletedLogs = $this->cleanupLogs();
        $deletedBackups = $this->cleanupBackups();
        
        $this->info("Cleanup selesai!");
        $this->info("Log dihapus: {$deletedLogs} file");
        $this->info("Backup dihapus: {$deletedBackups} file");
        
        return 0;
    }

    private function cleanupLogs()
    {
        $logPath = storage_path('logs');
        $deleted = 0;
        
        if (!File::exists($logPath)) {
            return $deleted;
        }
        
        $files = File::files($logPath);
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        
        foreach ($files as $file) {
            $fileTime = Carbon::createFromTimestamp(File::lastModified($file));
            
            if ($fileTime->lt($thirtyDaysAgo)) {
                File::delete($file);
                $deleted++;
                $this->line("Deleted log: " . $file->getFilename());
            }
        }
        
        return $deleted;
    }

    private function cleanupBackups()
    {
        $backupPath = storage_path('app/backups');
        $deleted = 0;
        
        if (!File::exists($backupPath)) {
            return $deleted;
        }
        
        $files = File::files($backupPath);
        $sevenDaysAgo = Carbon::now()->subDays(7);
        
        foreach ($files as $file) {
            $fileTime = Carbon::createFromTimestamp(File::lastModified($file));
            
            if ($fileTime->lt($sevenDaysAgo)) {
                File::delete($file);
                $deleted++;
                $this->line("Deleted backup: " . $file->getFilename());
            }
        }
        
        return $deleted;
    }
}
