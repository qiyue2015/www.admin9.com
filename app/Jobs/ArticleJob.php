<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

class ArticleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Article $article;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $url = 'https://www.yebaike.com/e/action/ShowInfo.php?classid=32&id='.$this->article->id;
        $response = Http::withoutVerifying()->get($url);
        try {
            $crawler = new Crawler();
            $crawler->addHtmlContent($response->body());
            $title = $crawler->filter('h1.title')->text();
            $date = $crawler->filterXPath('//div[@class="header"]/div/span[1]')->text();
            $categoryName = $crawler->filterXPath('//div[@class="header"]/div/span[2]/a')->text();
            $content = $crawler->filter('.article .text p')->each(function (Crawler $cr) {
                return '<p>'.$cr->html().'</p>';
            });

            $category = Category::firstOrCreate(['name' => $categoryName]);
            $this->article->fill([
                'category_id' => $category->id,
                'title' => $title,
                'checked' => true,
                'created_at' => now()->parse($date)->toDateTimeString(),
            ]);

            $subtable = $this->article->id % 10;
            DB::table('articles_'.$subtable)->updateOrInsert([
                'id' => $this->article->id,
            ], [
                'content' => implode(PHP_EOL, $content),
            ]);
        } catch (Throwable $e) {
            if ($e->getMessage() === 'The current node list is empty.') {
                $this->article->update([
                    'category_id' => $this->article->category_id + 1,
                ]);
            }
        }
    }
}
