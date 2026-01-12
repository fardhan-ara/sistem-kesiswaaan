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
        // Daily incremental backup at 00:00
        $schedule->command('backup:daily')->dailyAt('00:00');
        
        // Weekly full backup every Sunday at 02:00
        $schedule->command('backup:weekly')->weekly()->sundays()->at('02:00');
        
        // Monthly archive backup on last day of month at 23:00
        $schedule->command('backup:monthly')->monthlyOn(date('t'), '23:00');
        
        // Existing commands
        $schedule->command('backup:database')->daily();
        $schedule->command('cleanup:old-files')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
