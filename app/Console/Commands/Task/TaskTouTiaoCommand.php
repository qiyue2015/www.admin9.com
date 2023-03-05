<?php

namespace App\Console\Commands\Task;

use App\Ace\Horizon\CustomQueue;
use App\Jobs\Task\TaskTouTiaoListJob;
use App\Models\Task;
use Illuminate\Console\Command;

class TaskTouTiaoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:toutiao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '从头条采集关键词例表';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $list = Task::runStatus()->limit(500)->get();
        if ($list->isEmpty()) {
            $this->info('无可执行数据.');
            return;
        }

        $ids = collect($list)->pluck('id')->toArray();

        // 设置下次运行时间
        Task::whereIn('id', $ids)->update(['run_time' => now()->addDay()->timestamp]);

        $bar = $this->output->createProgressBar($list->count());
        collect($list)->each(function ($task) use ($bar) {
            TaskTouTiaoListJob::dispatch($task)->onQueue(CustomQueue::SPIDER_TOUTIAO_WENBA_QUEUE);
            $bar->advance();
        });
    }
}
