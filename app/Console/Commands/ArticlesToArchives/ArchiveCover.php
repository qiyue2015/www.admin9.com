<?php

namespace App\Console\Commands\ArticlesToArchives;

use App\Ace\Horizon\CustomQueue;
use App\Jobs\Init\ArchiveCoverJob;
use App\Models\Archive;
use Illuminate\Console\Command;

class ArchiveCover extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:cover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '处理提取封面';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        $lastId = Archive::whereHasCover(0)->where('is_publish', 1)->max('id');
        $count = Archive::whereHasCover(0)->where('is_publish', 1)->count();
        $bar = $this->output->createProgressBar($count);
        $star = 0;
        while ($star < $lastId) {
            $data = Archive::whereHasCover(0)
                ->where('is_publish', 1)
                ->where('id', '>', $star)
                ->orderBy('id')
                ->limit(500)
                ->get(['id', 'cover']);

            if ($data->isEmpty()) {
                break;
            }

            collect($data)->each(function ($archive) {
                ArchiveCoverJob::dispatch($archive)->onQueue(CustomQueue::LARGE_PROCESSES_QUEUE);
            });

            $star = $data->last()->id;
            $bar->advance($data->count());
        }
    }
}
