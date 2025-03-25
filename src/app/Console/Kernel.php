<?php

namespace App\Console;

use App\Console\Commands\Billar\InvoiceRecurringCommand;
use App\Console\Commands\Billar\PaymentReminder;
use App\Console\Commands\HourlyProcessInvoiceRemindersCommand;
use App\Jobs\SendReminderMessage;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel.
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        PaymentReminder::class,
        InvoiceRecurringCommand::class,
        HourlyProcessInvoiceRemindersCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('queue:work --queue=high,default --tries=2 --stop-when-empty')
            ->everyMinute()
            ->withoutOverlapping();

        $schedule->command('payment:reminder')
            ->daily();

        $schedule->command('invoice:recurring')
            ->daily();

        $schedule->command('hour:invoice_reminder')
            ->everyMinute()
            ->withoutOverlapping();

        $schedule->job(SendReminderMessage::class)->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}