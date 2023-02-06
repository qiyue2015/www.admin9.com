<?php

namespace App\Console\Commands\BaiduAi;

use App\Jobs\BaiduAi\BaiduAiCategoryJob;
use App\Jobs\BaiduAi\BaiduAiDescriptionJob;
use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
            $subtable = 'articles_'.$article->id % 10;
            $item = DB::table($subtable)->where('id', $article->id)->first();
            if ($item) {
                // 清空html和多作空格
                $string = strip_tags($item->content);
                $content = implode(PHP_EOL, array_filter(explode(PHP_EOL, $string)));

                // 处理分类
                if ($article->category_id === 0 && $article->status === 0) {
                    BaiduAiCategoryJob::dispatch($article, $content)->onQueue('just_for_category');
                }

                // 处理摘要
                if (in_array($article->status, [1, 4])) {
                    BaiduAiDescriptionJob::dispatch($article, $content)->onQueue('just_for_description');
                }

                // 关键词提取
                //if (in_array($article->status, [1, 3])) {
                //    $this->info('处理关键词');
                //}
            }
        });
    }

    private function query(): Article|\Illuminate\Database\Eloquent\Builder
    {
        return Article::query()->where('status', '<', 3);
    }
}
