<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class BackupMonthly extends Command
{
    protected $signature = 'backup:monthly';
    protected $description = 'Monthly archive database backup';

    public function handle()
    {
        $backupPath = storage_path('app/backups');
        
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }
        
        $date = date('Y-m-d_His');
        $fileName = "backup_monthly_{$date}.sql";
        $filePath = $backupPath . '/' . $fileName;
        
        $this->info('Creating monthly archive backup...');
        
        $tables = DB::select('SHOW TABLES');
        $sql = "-- Monthly Archive Backup\n-- Date: " . date('Y-m-d H:i:s') . "\n\n";
        
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
        
        // Keep all monthly backups (no cleanup)
        
        $this->info('Monthly backup completed: ' . $fileName . '.gz');
        return 0;
    }
}
