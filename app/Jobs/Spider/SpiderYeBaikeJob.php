<?php

namespace App\Jobs\Spider;

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
use Symfony\Component\DomCrawler\Crawler;

class SpiderYeBaikeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $link;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($link)
    {
        $this->link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::get($this->link);
        try {
            $crawler = new Crawler();
            $crawler->addHtmlContent($response->body());
            $title = $crawler->filter('.weui-c-title')->text();
            $date = $crawler->filterXPath('//div[@class="weui-c-content"]/div/span[1]')->text();
            $cover_url = $crawler->filter('.weui-c-article p>img')->attr('src');
            $content = $crawler->filter('.weui-c-article p')->each(function (Crawler $cr) {
                return '<p>'.$cr->html().'</p>';
            });

            $data = [
                'category_id' => 0,
                'title' => $title,
                'keywords' => '',
                'description' => '',
                'checked' => true,
                'status' => 0,
                'cover_url' => $cover_url,
                'source_name' => '',
                'author_name' => '',
                'updated_at' => now()->parse($date)->toDateTimeString(),
                'created_at' => now()->parse($date)->toDateTimeString(),
            ];

            DB::transaction(static function () use ($content, $data) {
                $article = new Article();
                $article->fill($data)->save();
                DB::table('articles_'.($article->id % 10))->updateOrInsert([
                    'id' => $article->id,
                ], [
                    'tags' => '',
                    'content' => implode(PHP_EOL, $content),
                ]);
            });
        } catch (\Throwable $e) {
            $this->fail($e);
        }
    }
}
