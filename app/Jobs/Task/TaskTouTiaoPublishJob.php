<?php

namespace App\Jobs\Task;

use App\Models\Archive;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Overtrue\Pinyin\Pinyin;

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
        // 从日志取出来例表
        $contents = getTaskLog($this->task->hash);

        // 取相似度大于80的第1条信息
        $item = collect($contents)
            ->map(function ($row) {
                similar_text($this->task->title, $row['title'], $percent); // 两个字符串的相似度
                $row['percent'] = $percent;
                return $row;
            })->filter(function ($row) {
                return $row['percent'] > 80; // 取出大于80分的
            })->sortByDesc(function ($row) {
                return $row['percent']; // 倒叙排一下
            })->first();

        if ($item) {
            // 文档表如果已发布就关闭
            if (Archive::whereTaskId($this->task->hash)->exists()) {
                $this->task->update(['status' => 0]);
            } else {
                // 去抓取内容回来
                $response = Http::getWithProxy($item['url']);
                if (preg_match('/"data":\{"answers":(.*?),"has_mor/i', $response->body(), $matches)) {
                    $answers = json_decode($matches[1]);
                    $contents = collect($answers)->map(function ($row) {
                        return '<div class="answer-box">'.$row->content.'</div>';
                    })->toArray();

                    $cover = makeTitleCover($this->task->title, true);

                    DB::transaction(function () use ($contents, $item, $cover) {
                        $writerArray = config('writer');
                        $archive = new Archive();
                        $archive->category_id = $this->getCategory($this->task->tags)->id;
                        $archive->title = $this->task->title;
                        $archive->tags = $this->task->tags;
                        $archive->description = str($item['summary'])->limit();
                        $archive->cover = $cover ?? null;
                        $archive->has_cover = (bool) $cover;
                        $archive->writer = $writerArray[array_rand($writerArray)];
                        $archive->task_id = $this->task->hash;
                        $archive->save();
                        $archive->extend()->create([
                            'content' => implode(PHP_EOL, $contents),
                        ]);

                        // 关闭任务
                        $this->task->update(['status' => 1]);
                    });
                } else {
                    Log::channel('spider')->info('正则提取【TaskTouTiaoPublishJob】', ['content' => $response->body()]);
                }
            }
        }
    }

    private function getCategory($string): Model|Category
    {
        $arr = explode(',', $string);
        return Category::firstOrCreate([
            'alias' => $arr[0],
            'parent_id' => 0,
        ], [
            'name' => $arr[0],
            'slug' => Pinyin::permalink($arr[0], ''),
            'children' => [],
            'parents' => [],
        ]);
    }
}
