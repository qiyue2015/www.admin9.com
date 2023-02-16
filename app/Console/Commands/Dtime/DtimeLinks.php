<?php

namespace App\Console\Commands\Dtime;

use App\Models\Dtime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DtimeLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dtime:links';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit', -1);

        // 固定的链接
        $urls = [
            '/feed.php', '/index.php',
            '/c/life/', '/c/travel/', '/c/meishi/', '/c/people/', '/c/info/', '/c/pic/', '/c/game/', '/c/car/',
        ];
        $this->info(PHP_EOL.'固定的链接');
        $bar1 = $this->output->createProgressBar(count($urls));
        collect($urls)->each(function ($url) use ($bar1) {
            $bar1->advance();
            \App\Jobs\Dtime\DtimeLinks::dispatch($url, true)->onQueue(CustomQueue::LARGE_PROCESSES_QUEUE);
        });

        // 数据库里的链接
        $this->info(PHP_EOL.'数据表的链接');
        $lastId = Dtime::where('status', 0)->max('id');
        $count = Dtime::where('status', 0)->count();
        $bar = $this->output->createProgressBar($count);
        $star = 0;
        while ($star < $lastId) {
            $list = Dtime::where('id', '>', $star)
                ->where('status', 0)
                ->take(100)
                ->get();

            if ($list->isEmpty()) {
                break;
            }

            $star = $list->last()->id;
            $bar->advance($list->count());

            collect($list)->each(function ($row) use ($bar) {
                $bar->advance();
                \App\Jobs\Dtime\DtimeLinks::dispatch($row->url, false)->onQueue(CustomQueue::LARGE_PROCESSES_QUEUE);
            });
        }
    }

    private function checkLink($urlHash)
    {
        $key = 'dtime:link:'.$urlHash;
        return cache()->rememberForever($key, function () use ($urlHash) {
            return Dtime::whereUrlHash($urlHash)->exists();
        });
    }
}
