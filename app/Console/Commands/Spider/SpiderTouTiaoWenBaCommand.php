<?php

namespace App\Console\Commands\Spider;

use App\Ace\Horizon\CustomQueue;
use App\Jobs\Spider\SpiderTouTiaoWenBaJob;
use App\Models\Archive;
use Illuminate\Console\Command;

class SpiderTouTiaoWenBaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:toutiao-wenba';

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
        // 每分钟处理 30 条
        $list = Archive::where('is_html', 0)->limit(60)->get();
        if ($list->isNotEmpty()) {
            $bar = $this->output->createProgressBar($list->count());
            collect($list)->each(function ($archive) use ($bar) {
                $bar->advance();
                SpiderTouTiaoWenBaJob::dispatch($archive)->onQueue(CustomQueue::SPIDER_TOUTIAO_WENBA_QUEUE);
            });
        }
        //$lastId = Archive::max('id');
        //$count = Archive::count();
        //$star = 0;
        //$bar = $this->output->createProgressBar($count);
        //while ($star < $lastId) {
        //    $this->comment($star);
        //    $list = Archive::where('id', '>', $star)
        //        ->where('is_html', 0)
        //        ->take(10)->get();
        //    if ($list->isEmpty()) {
        //        break;
        //    }
        //
        //    foreach ($list as $archive) {
        //        $this->info($archive->title);
        //        SpiderTouTiaoWenBaJob::dispatch($archive);
        //    }
        //
        //    $bar->advance($list->count());
        //    $star = $list->last()->id;
        //}
    }
}
