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
        while ($star < 50) {
            $star++;
            $data[] = [
                'tags' => '',
                'status' => 0,
                'result' => '',
            ];
        }
        Photo::insert($data);

        $url = 'https://pixabay.com/api/';
        $list = Photo::where('status', false)->take(1000)->get();
        $bar = $this->output->createProgressBar($list->count());
        collect($list)->each(function ($row) use ($url, $bar) {
            $bar->advance();
            $row->update(['status' => 1]);
            SpiderPixabayJob::dispatch($url, $row->id)->onQueue('just_for_pixabay');
        });
    }
}
