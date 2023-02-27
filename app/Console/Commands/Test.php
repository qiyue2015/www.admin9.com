<?php

namespace App\Console\Commands;

use App\Ace\Horizon\CustomQueue;
use App\Jobs\Task\TaskTouTiaoPublishJob;
use App\Models\Archive;
use App\Models\Category;
use App\Models\Task;
use App\Repositories\TaskRepository;
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

            foreach ($list as $task) {
                TaskTouTiaoPublishJob::dispatch($task)->onQueue(CustomQueue::SPIDER_TOUTIAO_WENBA_QUEUE);
            }

            $star = $list->last()->id;
            $bar->advance($list->count());
        }
    }
}
