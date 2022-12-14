<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // 每分钟运行一次采集
        $schedule->command('spider:yebaike')->everyMinute()->withoutOverlapping();

        // 每天早上 2 点到 6 点每 5 分钟执行脚本
        $schedule->command('article:delete-same')->between('2:00', '6:00')->everyFiveMinutes();

        // 从百度经验获取验证集数据
        $schedule->command('dataset:init-valid')->everyThreeMinutes()->withoutOverlapping();
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
