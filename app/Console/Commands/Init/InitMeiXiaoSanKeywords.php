<?php

namespace App\Console\Commands\Init;

use App\Jobs\Init\InitMeiXiaoSanKeywordsJob;
use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class InitMeiXiaoSanKeywords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:meixiaosan-keywords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $count = $this->query()->count();
        $lastId = $this->query()->max('id');
        $bar = $this->output->createProgressBar($count);
        $star = 0;
        while ($star < $lastId) {
            $list = $this->query()->where('id', '>', $star)->take(500)->get(['id', 'title']);
            $star = $list->last()->id;
            $bar->advance($list->count());
            collect($list)->each(function ($article) {
                InitMeiXiaoSanKeywordsJob::dispatch($article)->onQueue('just_for_article');
            });
        }
    }

    private function query(): Article|Builder
    {
        return Article::checked()->where('status', 0);
    }
}
