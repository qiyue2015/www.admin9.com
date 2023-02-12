<?php

namespace App\Console\Commands\Spider;

use App\Jobs\Spider\SpiderPixabayJob;
use App\Models\Photo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use function Clue\StreamFilter\fun;

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
        $star = 0;
        $data = [];
        while ($star < 60) {
            $star++;
            $data[] = [
                'tags' => '',
                'status' => 0,
                'result' => '',
            ];
        }
        Photo::insert($data);

        $url = 'https://pixabay.com/api/';
        $lastId = Photo::where('status', false)->max('id');
        $count = Photo::where('status', false)->count();
        $bar = $this->output->createProgressBar($count);
        $star = Photo::where('status', false)->min('id');
        while ($star < $lastId) {
            $star++;
            $bar->advance();
            SpiderPixabayJob::dispatch($url, $star)->onQueue('just_for_pixabay');
        }
    }
}
