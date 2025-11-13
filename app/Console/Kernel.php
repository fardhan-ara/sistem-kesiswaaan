<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\BackupDatabase::class,
        Commands\CleanupOldFiles::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('backup:database')->daily();
        $schedule->command('cleanup:old-files')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
