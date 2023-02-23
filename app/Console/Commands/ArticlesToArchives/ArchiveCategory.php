<?php

namespace App\Console\Commands\ArticlesToArchives;

use App\Ace\Horizon\CustomQueue;
use App\Jobs\Init\ArchiveCategoryJob;
use App\Jobs\Init\ArchiveCoverJob;
use App\Models\Archive;
use Illuminate\Console\Command;

class ArchiveCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:category';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '处理标签同时对信息归类';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        $lastId = Archive::whereCategoryId(0)->max('id');
        $count = Archive::whereCategoryId(0)->count();
        $bar = $this->output->createProgressBar($count);
        $star = 0;
        while ($star < $lastId) {
            $data = Archive::whereCategoryId(0)
                ->where('id', '>', $star)
                ->orderBy('id')
                ->limit(500)
                ->get(['id', 'tags']);

            if ($data->isEmpty()) {
                break;
            }

            collect($data)->each(function ($archive) {
                ArchiveCategoryJob::dispatch($archive)->onQueue(CustomQueue::LARGE_PROCESSES_QUEUE);
            });

            $star = $data->last()->id;
            $bar->advance($data->count());
        }
    }
}
