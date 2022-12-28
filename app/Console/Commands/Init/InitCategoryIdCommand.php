<?php

namespace App\Console\Commands\Init;

use App\Jobs\InitCategoryIdJob;
use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class InitCategoryIdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:category-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '通过百度自定义标签模型对内容进行分类';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Article::whereCategoryId(99)
                ->orderByDesc('id')
                ->limit(1000)
                ->each(function ($article) {
                    InitCategoryIdJob::dispatch($article);
                });
    }
}
