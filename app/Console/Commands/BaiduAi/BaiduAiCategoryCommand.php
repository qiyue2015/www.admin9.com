<?php

namespace App\Console\Commands\BaiduAi;

use App\Jobs\BaiduAi\BaiduAiCategoryJob;
use App\Models\Article;
use Illuminate\Console\Command;

class BaiduAiCategoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baidu-ai:category {limit=500 : 每批处理数量}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '通过百度AI进行分类';

    private function query()
    {
        return Article::query()->where('category_id', 0)->checked();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $limit = (int) $this->argument('limit');
        $star = 0;
        $lastId = $this->query()->max('id');
        $count = $this->query()->count();
        $bar = $this->output->createProgressBar($count);
        $this->info('待处理：'.$count);
        $this->info('最大ID：'.$lastId);
        while ($star < $lastId) {
            $list = $this->query()->where('id', '>', $star)->take($limit)->get();
            $star = $list->last()->id;
            $bar->advance($list->count());
            collect($list)->each(function ($article) {
                BaiduAiCategoryJob::dispatch($article)->onQueue('just_for_category');
            });
        }
    }
}
