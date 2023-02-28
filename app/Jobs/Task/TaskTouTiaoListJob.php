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
        $this->task->increment('run_num', 1, ['run_time' => now()->timestamp]);

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
            $maps = collect($response->json('data'))->filter(function ($row) {
                return isset($row['display_type_self'], $row['display']); // 只需要问答的内容
            });

            $list = collect($maps)->map(function ($row) {
                return [
                    'title' => $row['title'],
                    'summary' => $row['display']['summary']['text'],
                    'item_id' => $row['item_id'],
                    'source' => $row['source'],
                    'url' => $row['url'] ?? '',
                ];
            })->toArray();

            if ($list) {
                $list = array_values($list);
                $this->task->update(['contents' => $list]);
                TaskTouTiaoPublishJob::dispatch($this->task);
            }
        }
    }
}
