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
                $this->info($row->title);
                $contents = collect($row->contents)->map(function ($val) {
                    unset($val['large_image_url'], $val['has_image'], $val['show_tag_list'], $val['image_list']);
                    return $val;
                })->toArray();
                $row->update(['contents' => $contents]);
            }

            $star = $list->last()->id;
            $bar->advance($list->count());
        }
    }
}
