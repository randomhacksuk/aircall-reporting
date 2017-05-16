<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\OldAircallContacts',
        'App\Console\Commands\AircallContacts',
        'App\Console\Commands\OldAircallCalls',
        'App\Console\Commands\AircallCalls',
        'App\Console\Commands\OldAircallUsers',
        'App\Console\Commands\AircallUsers',
        'App\Console\Commands\OldAircallNumbers',
        'App\Console\Commands\AircallNumbers',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('aircall_calls')
                 ->everyMinute();
        $schedule->command('aircall_users')
                 ->everyMinute();
        $schedule->command('aircall_numbers')
                 ->everyMinute();
        $schedule->command('aircall_contacts')
                 ->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
