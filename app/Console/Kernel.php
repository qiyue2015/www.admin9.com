<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

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

        // 每5分钟生成一次首页
        $schedule->command('generate-html')->everyFiveMinutes()->withoutOverlapping();

        // 每分钟运行一次采集
        //$schedule->command('spider:yebaike')->everyMinute()->withoutOverlapping();
        //$schedule->command('spider:pixabay')->everyMinute()->withoutOverlapping();
        $schedule->command('extract:proxy')->everySixHours()->withoutOverlapping();
        $schedule->command('spider:toutiao-wenba')->everyMinute()->withoutOverlapping();

        // 每天早上 2 点到 6 点每 5 分钟执行脚本
        //$schedule->command('article:delete-same')->between('2:00', '6:00')->everyFiveMinutes()->withoutOverlapping();

        // 每分钟执行
        // TODO：百度只有 5 万条免费额度
        //$schedule->command('baidu-ai:extract --limit=10000')->between('23:59', '5:00')->everyTwoHours()->withoutOverlapping();

        // 单独的分类处理 45 W
        //$schedule->command('baidu-ai:category --limit=920')->everyThreeMinutes()->withoutOverlapping();
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
