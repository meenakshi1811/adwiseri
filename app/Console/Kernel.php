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
        Commands\ExportCron::class,
        Commands\SendScheduledReports::class,
        Commands\SendPaymentReminderEmails::class,
    ];


    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('export:cron')->monthlyOn(date("t"), '23:00:00');
        $schedule->command('reports:dispatch-scheduled')->everyThirtyMinutes();
        $schedule->command('subscriptions:send-reminders')->dailyAt('00:00');
        $schedule->command('payments:send-reminders')->dailyAt('01:00');
        $schedule->command('activate:system-ops')->daily()->when(function () {
            return now()->isSameDay('2025-09-13');
        });
        // $schedule->command('export:cron')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
