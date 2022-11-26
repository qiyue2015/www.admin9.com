<?php

namespace App\Console\Commands\Article;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class YeBaiKeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:yebaike
                            {--init= : 类型 1 初始化id}
                            {--num=10 : 数量}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected array $categoryIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 24, 26, 27, 28, 29, 30, 31, 32];

    /**
     * @return void
     */
    public function handle()
    {
        $init = (int) $this->option('init');
        $num = (int) $this->option('num');
        if ($init === 1) {
            $this->init($num);
        }
        if ($init === 2) {
            $this->checkLink($num);
        }
    }

    protected function init($num): void
    {
        $star = 1;
        $maxId = (3502440 - 80); // 3502440
        $bar = $this->output->createProgressBar($maxId);
        while ($star <= $maxId) {
            $data = [];
            for ($i = 0; $i < $num; $i++) {
                $data[] = [
                    'title' => '待采集',
                    'channel_id' => 1,
                    'category_id' => 1,
                    'checked' => false,
                ];
            }

            Article::insert($data);

            $star += $num;
            $bar->advance($num);
        }
    }

    protected function checkLink($num): void
    {
        $count = Article::where('checked', 0)->where('category_id', 1)->count();
        $id = Article::where('checked', 0)->where('category_id', 1)->min('id');
        $bar = $this->output->createProgressBar($count);

        $this->line('开始');
        $this->info('初始ID'.$id);
        $this->info('待数据：'.$count);
        $this->info('每次运行：'.$num);

        $i = 0;
        while ($i < $count) {
            $i++;
            $bar->advance($num);

            $list = Article::where('checked', 0)
                ->where('id', '>', $id)
                ->where('category_id', 1)
                ->limit($num)
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
                })->onQueue('just_for_article');
            }

        }
    }
}
