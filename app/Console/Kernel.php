<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    // tambahin baris ini
    protected $commands = [
        \App\Console\Commands\SendDocumentReminders::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('reminders:send')->dailyAt('08:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
