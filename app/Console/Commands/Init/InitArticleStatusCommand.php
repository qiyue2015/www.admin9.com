<?php

namespace App\Console\Commands\Init;

use App\Models\Article;
use App\Models\Dataset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InitArticleStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:article-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初始化所有文章的状态';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        ini_set('memory_limit', -1);

        $this->info('初始化文章状态...');

        $count = Article::checked()->count();
        $bar = $this->output->createProgressBar($count);
        $star = 0;
        $lastId = Article::checked()->max('id');
        while ($star < $lastId) {
            $list = Article::checked()
                ->where('id', '>', $star)
                ->where('status', '<', 3)
                ->take(2000)
                ->get();

            $bar->advance($list->count());
            $star = $list->last()->id;

            $update = []; // 相同状态放到一个数组里
            foreach ($list as $row) {
                $status = 0;

                // 有分类
                if ($row->category_id > 0) {
                    $status += 1;
                }

                // 有摘要
                if ($row->description) {
                    //$status += 2;
                }

                // 有关键词
                if ($row->keywords) {
                    $status += 3;
                }

                $update[$status][] = $row->id;
            }

            foreach ($update as $key => $ids) {
                Article::whereIn('id', $ids)->update(['status' => $key]);
            }
        }
    }
}
