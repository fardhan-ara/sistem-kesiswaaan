<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class BackupWeekly extends Command
{
    protected $signature = 'backup:weekly';
    protected $description = 'Weekly full database backup';

    public function handle()
    {
        $backupPath = storage_path('app/backups');
        
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }
        
        $date = date('Y-m-d_His');
        $fileName = "backup_weekly_{$date}.sql";
        $filePath = $backupPath . '/' . $fileName;
        
        $this->info('Creating weekly full backup...');
        
        $tables = DB::select('SHOW TABLES');
        $sql = "-- Weekly Full Backup\n-- Date: " . date('Y-m-d H:i:s') . "\n\n";
        
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
            
            $rows = DB::table($tableName)->get();
            foreach ($rows as $row) {
                $values = array_map(fn($v) => is_null($v) ? 'NULL' : "'" . addslashes($v) . "'", (array)$row);
                $sql .= "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
            }
            $sql .= "\n";
        }
        
        File::put($filePath, $sql);
        
        // Compress
        $fp = gzopen($filePath . '.gz', 'w9');
        gzwrite($fp, File::get($filePath));
        gzclose($fp);
        File::delete($filePath);
        
        // Clean old backups (keep 8 weeks)
        $files = File::files($backupPath);
        foreach ($files as $file) {
            if (str_contains($file->getFilename(), 'weekly') && 
                $file->getMTime() < strtotime('-56 days')) {
                File::delete($file->getPathname());
            }
        }
        
        $this->info('Weekly backup completed: ' . $fileName . '.gz');
        return 0;
    }
}
