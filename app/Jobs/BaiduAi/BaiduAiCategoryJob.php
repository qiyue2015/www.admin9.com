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
use RuntimeException;

class BaiduAiCategoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $key = 'jcTPUIkefcCLgxQbF5DVz9By';

    protected string $secret = '6Kdm7fbNEOAeGqOzqCBV2UuEtbyLLjna';

    private Article $article;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    private function getToken()
    {
        return Cache::remember('baidu-ai-token', now()->addDays(30), function () {
            $url = 'https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id='.$this->key.'&client_secret='.$this->secret;
            return Http::get($url)->json('access_token');
        });
    }

    private function subQuery(): \Illuminate\Database\Query\Builder
    {
        $subtable = 'articles_'.$this->article->id % 10;
        return DB::table($subtable)->where('id', $this->article->id);
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
        if ($this->article->title && $this->article->description) {
            try {
                // 取副表内容
                $subItem = $this->subQuery()->first();
                if ($subItem) {
                    $content = strip_tags($subItem->content);
                    $content = trim($content);
                    $content = preg_replace("[\r\n|\r|\t|\s]", " ", $content);
                    $content = $this->deleteHtml($content);
                    $content = Str::limit($content, 1000);
                    Log::error('xxx', [$content]);

                    $url = 'https://aip.baidubce.com/rpc/2.0/nlp/v1/topic?charset=UTF-8&access_token='.$this->getToken();
                    $response = Http::asJson()->post($url, [
                        'title' => $this->article->title,
                        'content' => Str::limit($content, 1000),
                    ]);
                    $result = $response->object();
                    if (isset($result->error_code)) {
                        throw new RuntimeException($response->body());
                    }

                    // 设置分类
                    $topic = collect($result->item->lv1_tag_list)->first();
                    $category = $this->getCategory($topic->tag);
                    $this->article->category_id = $category->id;
                    $this->article->save();

                    // 设置 TAGS
                    if ($result->item->lv2_tag_list) {
                        $tags = collect($result->item->lv2_tag_list)->map(function ($val) {
                            return $val->tag;
                        })->toArray();
                        $this->subQuery()->update(['tags' => implode(',', $tags)]);
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

    private function deleteHtml($str): string
    {
        $str = trim($str); //清除字符串两边的空格
        $str = preg_replace("/\t/", "", $str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。
        $str = preg_replace("/\r\n/", "", $str);
        $str = preg_replace("/\r/", "", $str);
        $str = preg_replace("/\n/", "", $str);
        $str = preg_replace("/ /", "", $str);
        $str = preg_replace("/  /", "", $str);  //匹配html中的空格
        return trim($str); // 返回字符串
    }
}
