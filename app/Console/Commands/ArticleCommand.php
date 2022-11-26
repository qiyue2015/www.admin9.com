<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ArticleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:yebaike
                            {--init= : 操作类型 1 初始化}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected array $categoryIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 24, 26, 27, 28, 29, 30, 31, 32];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $maxId = Article::orderBy('id', 'DESC')->value('id');
        $bar = $this->output->createProgressBar($maxId);

        $id = 0;
        while ($id < $maxId) {
            $list = Article::where('checked', 0)
                ->where('id', '>', $id)
                ->where('category_id', 1)
                ->limit(100)
                ->get();

            if (empty($list)) {
                dd('all ok.');
            }

            foreach ($list as $row) {
                $id = $row->id;
                $url = 'https://www.yebaike.com/e/action/ShowInfo.php?classid=32&id='.$id;
                dispatch(static function () use ($url, $row) {
                    $response = Http::withoutVerifying()->get($url);
                    if (str()->contains($response->body(), '此信息不存在')) {
                        $row->category_id++;
                        $row->save();
                    } else {
                        try {
                            $crawler = new Crawler();
                            $crawler->addHtmlContent($response->body());
                            $title = $crawler->filter('h1.title')->text();
                            $date = $crawler->filterXPath('//div[@class="header"]/div/span[1]')->text();
                            $categoryName = $crawler->filterXPath('//div[@class="header"]/div/span[2]/a')->text();
                            $content = $crawler->filter('.article .text p')
                                ->each(function (Crawler $cr) {
                                    return '<p>'.$cr->html().'</p>';
                                });

                            $category = Category::firstOrCreate(['name' => $categoryName]);

                            $row->category_id = $category->id;
                            $row->title = $title;
                            $row->checked = true;
                            $row->created_at = now()->parse($date)->toDateTimeString();
                            $row->save();

                            $subtable = $row->id % 10;
                            DB::table('articles_'.$subtable)->updateOrInsert([
                                'id' => $row->id,
                            ], [
                                'content' => implode(PHP_EOL, $content),
                            ]);

                        } catch (\Exception $e) {
                            \Log::debug($e->getMessage());
                        }
                    }
                });
            }

            $bar->advance(100);
        }
    }
}
