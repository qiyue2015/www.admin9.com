<?php

namespace App\Jobs\BaiduAi;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Psr\SimpleCache\InvalidArgumentException;
use RuntimeException;

class BaiduAiCategoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Article $article;

    protected string $content;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Article $article, string $content)
    {
        $this->article = $article;
        $this->content = $content;
    }

    private function getToken()
    {
        return Cache::remember('baidu-ai-token', now()->addDays(30), static function () {
            $url = 'https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id='.config('baidu-ai.nlp.key').'&client_secret='.config('baidu-ai.nlp.secret');
            return Http::get($url)->json('access_token');
        });
    }

    public function getCategory($name)
    {
        return Cache::rememberForever('category:'.$name, static function () use ($name) {
            return Category::firstOrCreate(['name' => $name], [
                'num' => 0,
                'is_show' => 1,
                'baike_classid' => 0,
                'children' => [],
                'parents' => [],
            ]);
        });
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Illuminate\Contracts\Redis\LimiterTimeoutException
     */
    public function handle(): void
    {
        if ($this->article->title && $this->content) {
            try {
                $url = 'https://aip.baidubce.com/rpc/2.0/nlp/v1/topic?charset=UTF-8&access_token='.$this->getToken();
                $data = [
                    'title' => Str::limit($this->article->title, 40, ''),
                    'content' => Str::limit($this->content, 3000),
                ];
                $response = Http::asJson()->post($url, $data);
                $result = $response->object();
                if (isset($result->error_code)) {
                    // token 过期
                    if ($result->error_code === 110) {
                        cache()->delete('baidu-ai-token');
                        $this->release(10);
                    } else {
                        Log::error('[提取分类]'.$result->error_msg, $data);
                        throw new RuntimeException($response->body());
                    }
                } else {
                    // 设置分类
                    $topic = collect($result->item->lv1_tag_list)->first();
                    $category = $this->getCategory($topic->tag);
                    $this->article->category_id = $category->id;
                    $this->article->increment('status', 1, [
                        'category_id' => $category->id,
                    ]);

                    // 设置 TAGS
                    if ($result->item->lv2_tag_list) {
                        $tags = collect($result->item->lv2_tag_list)->map(function ($val) {
                            return $val->tag;
                        });
                        DB::table('articles_'.($this->article->id % 10))
                            ->where('id', $this->article->id)
                            ->update([
                                'tags' => implode(',', $tags->toArray()),
                            ]);
                    }
                }
            } catch (\Exception $exception) {
                $this->fail($exception);
            }
        } else {
            $this->article->checked = 0;
            $this->article->save();
        }
    }
}
