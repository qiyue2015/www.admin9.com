<?php

namespace App\Jobs\Task;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class TaskTouTiaoListJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $url = 'https://search5-search-lq.toutiaoapi.com/s/search_wenda/api/related_questions';

    protected Task $task;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle(): void
    {
        $query = [
            'version_code' => '9.1.9',
            'app_name' => 'news_article',
            'app_version' => '9.1.9',
            'carrier_region' => 'CN',
            'device_id' => '314947703983'.random_int(1000, 9999),
            'device_platform' => 'iphone',
            'enable_miaozhen_page' => 1,
            'enter_from' => 'search_result',
            'keyword' => $this->task->title,
        ];

        $response = Http::getWithProxy($this->url, $query);
        if ($response->json('data')) {
            // 筛选问答的内容
            $maps = collect($response->json('data'))->filter(function ($row) {
                return isset($row['display_type_self'], $row['display']);
            });

            $list = collect($maps)->map(function ($row) {
                return [
                    'title' => $row['title'],
                    'summary' => $row['display']['summary']['text'],
                    'item_id' => $row['item_id'],
                    'source' => $row['source'],
                    'url' => $row['url'] ?? '',
                ];
            });

            if ($list->isNotEmpty()) {
                // 增加执行次数 和最后运行时间
                $this->task->increment('run_num', 1, ['run_time' => now()->addDay()->timestamp]);

                // 写入日志备用
                $list = array_values($list->toArray());
                setTaskLog($this->task->hash, $list);

                // 发布内容
                TaskTouTiaoPublishJob::dispatch($this->task);
            }
        }
    }
}
