<?php

namespace App\Console\Commands\Init;

use App\Ace\Horizon\CustomQueue;
use App\Jobs\GenerateTitleCoverJob;
use App\Models\Archive;
use App\Models\Article;
use Illuminate\Console\Command;

class InitArchiveCover extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:archive-cover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成标题封面';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit', -1);

        $count = Archive::whereHasCover(0)->count();
        $bar = $this->output->createProgressBar($count);

        $star = 0;
        $lastId = Archive::whereHasCover(0)->max('id');
        while ($star < $lastId) {
            $list = Archive::whereHasCover(0)
                ->where('id', '>', $star)
                ->take(500)
                ->get(['id', 'title']);
            if ($list->isEmpty()) {
                break;
            }

            foreach ($list as $row) {
                GenerateTitleCoverJob::dispatch($row)->onQueue(CustomQueue::LARGE_PROCESSES_QUEUE);
            }

            $star = $list->last()->id;
            $bar->advance($list->count());
        }
    }
}
