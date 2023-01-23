<?php

namespace App\Console\Commands\BaiduAi;

use App\Jobs\BaiduAi\BaiduAiCategoryJob;
use App\Models\Article;
use Illuminate\Console\Command;
use function Clue\StreamFilter\fun;

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
    protected $description = 'Command description';

    private function query()
    {
        return Article::query()->where('category_id', 0);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $star = 0;
        $lastId = $this->query()->max('id');
        $count = $this->query()->count();
        $bar = $this->output->createProgressBar($count);
        while ($star < $lastId) {
            $list = $this->query()->where('id', '>', $star)->take(10)->get();
            $star = $list->last()->id;
            $bar->advance($list->count());
            collect($list)->each(function ($article) {
                BaiduAiCategoryJob::dispatch($article);
            });
        }
    }
}
