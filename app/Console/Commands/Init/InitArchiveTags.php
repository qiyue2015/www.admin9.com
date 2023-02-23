<?php

namespace App\Console\Commands\Init;

use App\Jobs\Init\InitArchiveCoverJob;
use App\Jobs\Init\InitArchiveTagsJob;
use App\Models\Archive;
use Illuminate\Console\Command;

class InitArchiveTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:archive-tags';

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

        ini_set('memory_limit', '-1');
        $lastId = Archive::where('is_publish', 1)->max('id');
        $count = Archive::where('is_publish', 1)->count();
        $bar = $this->output->createProgressBar($count);
        $star = 0;
        while ($star < $lastId) {
            $data = Archive::where('is_publish', 1)
                ->where('id', '>', $star)
                ->orderBy('id')
                ->limit(500)
                ->get(['id', 'tagging']);

            if ($data->isEmpty()) {
                break;
            }

            collect($data)->each(function ($archive) {
                InitArchiveTagsJob::dispatch($archive);
            });

            $star = $data->last()->id;
            $bar->advance($data->count());
        }
    }
}
