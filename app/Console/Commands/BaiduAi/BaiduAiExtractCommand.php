<?php

namespace App\Console\Commands\BaiduAi;

use App\Ace\Horizon\CustomQueue;
use App\Jobs\BaiduAi\BaiduAiCategoryJob;
use App\Jobs\BaiduAi\BaiduAiDescriptionJob;
use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaiduAiExtractCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baidu-ai:extract {--limit=100 : 每次执行数量}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '通过百度AI处理文章分类、摘要。（每次默认--limit=100）';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $limit = (int) $this->option('limit');
        $list = $this->query()->take($limit)->get();
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

                // 处理分类(暂时当作TAGS)
                if (!$article->tags) {
                    BaiduAiCategoryJob::dispatch($article, $content)->onQueue(CustomQueue::CATEGORY_UPDATE_QUEUE);
                }

                // 处理摘要
                if ($article->status < 3) {
                    BaiduAiDescriptionJob::dispatch($article, $content)->onQueue(CustomQueue::DESCRIPTION_UPDATE_QUEUE);
                }

                // 关键词提取
                //if (in_array($article->status, [0, 1, 2, 3])) {
                //    $this->info('处理关键词');
                //}
            } else {
                $article->update(['checked' => false]);
            }
        });
    }

    private function query(): Article|\Illuminate\Database\Eloquent\Builder
    {
        return Article::query()->checked();
    }
}
