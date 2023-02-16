<?php

namespace App\Console\Commands\BaiduAi;

use App\Ace\Horizon\CustomQueue;
use App\Jobs\BaiduAi\BaiduAiCategoryJob;
use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BaiduAiCategoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baidu-ai:category';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '百度分类';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $list = Article::checked()->where('status', 0)->take(9200)->get();
        if ($list->isNotEmpty()) {
            $bar = $this->output->createProgressBar($list->count());
            collect($list)->each(function ($article) use ($bar) {
                $bar->advance();

                // 副表取内容
                $subtable = 'articles_'.$article->id % 10;
                $item = DB::table($subtable)->where('id', $article->id)->first();
                if ($item && $item->content) {
                    // 清空html和多余空格换车
                    $string = str_replace([' ', '\t', '\r\n', '\r', '\n'], "", strip_tags($item->content));
                    $content = trim($string);
                    BaiduAiCategoryJob::dispatch($article, $content)->onQueue(CustomQueue::CATEGORY_UPDATE_QUEUE);
                }
            });
        }
    }
}
