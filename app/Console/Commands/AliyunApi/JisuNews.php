<?php

namespace App\Console\Commands\AliyunApi;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class JisuNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aliyun:jishu-news';

    /**
     * 新闻API_头条新闻_热门头条新闻查询-极速数据
     * https://market.aliyun.com/products/57126001/cmapi011178.html
     * @var string
     */
    protected string $url = 'https://jisunews.market.alicloudapi.com/news/get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '通过阿里云新闻API获取内容';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::withoutVerifying()
            ->timeout(30)
            ->asJson()
            ->withHeaders(['Authorization' => 'APPCODE '.config('alicloudapi.jishu-news')])
            ->get($this->url, [
                'channel' => '科技',
                'num' => 40,
                'start' => 0,
            ]);
        collect($response->json('result.list'))
            ->each(function ($row) {
                // 标题去重
                if (Article::whereTitle($row['title'])->exists()) {
                    return;
                }
                $category = Category::firstOrCreate(['name' => $row['category']]);
                $article = new Article();
                $article->category_id = $category->id;
                $article->title = $row['title'];
                $article->source_name = $row['src'];
                $article->cover_url = $row['pic'];
                $article->updated_at = now()->parse($row['time'])->toDateTimeString();
                if ($article->save()) {
                    // 内容数据写入副表
                    $subtable = $article->id % 10;
                    DB::table('articles_'.$subtable)->insert([
                        'id' => $article->id,
                        'content' => $row['content'],
                        'source_url' => $row['url'],
                    ]);
                }
            });
    }
}
