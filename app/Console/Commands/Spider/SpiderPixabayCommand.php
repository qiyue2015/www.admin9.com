<?php

namespace App\Console\Commands\Spider;

use App\Models\Photo;
use Illuminate\Console\Command;

class SpiderPixabayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:pixabay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        ini_set('memory_limit', -1);
        $lastId = 7783570;
        $page_size = 2000;
        $star = 0;
        $bar = $this->output->createProgressBar($lastId);
        while ($star < $lastId) {
            $star++;
            $bar->advance($page_size);
            $list = [];
            for ($i = 0; $i < $page_size; $i++) {
                $list[] = [
                    'tags' => '',
                    'status' => 0,
                    'result' => '',
                ];
            }
            Photo::insert($list);
        }
    }
}
