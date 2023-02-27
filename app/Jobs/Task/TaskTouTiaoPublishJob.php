<?php

namespace App\Jobs\Task;

use App\Models\Archive;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TaskTouTiaoPublishJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     *  调用百度的 API 对比标题
     *
     * @return void
     * @throws \Throwable
     */
    public function handle()
    {
        // 取相似度大于80的第1条信息
        $item = collect($this->task->contents)
            ->map(function ($row) {
                // 根据两个字符串得到相似度
                similar_text($this->task->title, $row['title'], $percent);
                $row['percent'] = $percent;
                return $row;
            })
            ->filter(function ($row) {
                // 取出大于80分的
                return $row['percent'] > 80;
            })
            ->sortByDesc(function ($row) {
                // 倒叙排一下
                return $row['percent'];
            })
            ->first();

        if ($item && !Archive::whereTaskId($this->task->hash)->exists()) {
            // 去抓取内容回来
            $response = Http::getWithProxy($item['url']);
            if (preg_match('/"data":\{"answers":(.*?),"has_mor/i', $response->body(), $matches)) {
                $answers = json_decode($matches[1]);
                $contents = collect($answers)->map(function ($row) {
                    return '<div class="answer-box">'.$row->content.'</div>';
                })->toArray();
                \DB::transaction(function () use ($contents, $item) {
                    $archive = Archive::create([
                        'title' => $this->task->title,
                        'tags' => $this->task->tags,
                        'description' => $item['summary'],
                        'task_id' => $this->task->hash,
                    ]);
                    $archive->extend()->create([
                        'content' => implode(PHP_EOL, $contents),
                    ]);

                    \DB::insert("INSERT INTO task_docs SELECT * FROM task_entries WHERE id={$this->task->id};");
                    // 归档后删除任务记录
                    $this->task->delete();
                });
            }
        }
    }
}
