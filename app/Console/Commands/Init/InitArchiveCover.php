<?php

namespace App\Console\Commands\Init;

use App\Jobs\Init\InitArchiveCoverJob;
use App\Models\Archive;
use Illuminate\Console\Command;

class InitArchiveCover extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:cover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '处理封面';

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
                InitArchiveCoverJob::dispatch($archive);
            });

            $star = $data->last()->id;
            $bar->advance($data->count());
        }
    }
}
