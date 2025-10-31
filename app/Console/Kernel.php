<?php
namespace App\Console;

use App\Models\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register commands here.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            // publish events if it's time
            Event::where('status', Event::STATUS_DRAFT)
                ->whereNotNull('publish_at')
                ->where('publish_at', '<=', now())
                ->update(['status' => Event::STATUS_PUBLISHED]);

            // update status to expired if needed
            Event::where('status', Event::STATUS_PUBLISHED)
                ->whereNotNull('expire_at')
                ->where('expire_at', '<=', now())
                ->update(['status' => Event::STATUS_EXPIRED]);
        })->everyMinute();
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
