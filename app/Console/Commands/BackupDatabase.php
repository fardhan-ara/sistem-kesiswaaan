<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Backup database to storage/backups';

    public function handle()
    {
        $filename = 'backup-' . date('Y-m-d-His') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');

        $command = sprintf(
            'mysqldump -h %s -u %s -p%s %s > %s',
            $host,
            $username,
            $password,
            $database,
            $path
        );

        exec($command, $output, $result);

        if ($result === 0) {
            $this->info('Database backup berhasil: ' . $filename);
        } else {
            $this->error('Database backup gagal');
        }

        return $result;
    }
}
