<?php

namespace App\Console\Commands;

use App\Ace\Horizon\CustomQueue;
use App\Models\Archive;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private function query(): \Illuminate\Database\Eloquent\Builder|Archive
    {
        return Task::where('run_num', '>', 0);
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
        $lastId = $this->query()->max('id');
        $count = $this->query()->count();
        $bar = $this->output->createProgressBar($count);
        $star = 0;
        while ($star < $lastId) {
            $list = $this->query()->where('id', '>', $star)->limit(100)->get();
            if ($list->isEmpty()) {
                break;
            }

            foreach ($list as $row) {
                $contents = collect($row->contents)->map(function ($row) {
                    $publish_time = (now()->parse($row['datetime'])->timestamp) - random_int(300, 720);
                    return [
                        'title' => $row['title'],
                        'summary' => $row['summary'],
                        'publish_time' => now()->parse($publish_time)->format('Y-m-d H:i:s'),
                        'source' => $row['source'],
                        'item_id' => $row['item_id'],
                        'url' => $row['url'],
                    ];
                })->toArray();
                $row->update(['contents' => $contents]);
            }

            $star = $list->last()->id;
            $bar->advance($list->count());
        }
    }
}
