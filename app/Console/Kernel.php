<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\CrudGenerator::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('worldcup:sync')->everyMinute();
        $schedule->command('weekly-best:generate')->weekly();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}