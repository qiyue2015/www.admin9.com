<?php

namespace App\Console\Commands\Article;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteSameCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:delete-same';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '删除文章表重复标题';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->comment('删除文章表重复标题…');

        $star = 1;
        $limit = 10;
        $page = 0;
        while ($star) {
            $this->info('正在执行（'.$star.'）');

            $skip = $page * $limit;
            $list = Article::select(DB::raw('COUNT(title) as dd, title'))
                ->groupBy('title')
                ->orderByDesc('dd')
                ->skip($skip)
                ->take($limit)
                ->get();
            dd($list);

            if ($list->isEmpty()) {
                $this->info('全部执行完毕.');
                break;
            }

            $page++;

            foreach ($list as $row) {
                $star = $row->id;
                $title = $row->title;

                if ($row->dd === 1) {
                    break 2;
                }

                //dispatch(static function () use ($title) {
                //    Article::whereTitle($title)->get(['id', 'title'])->each(function ($row, $index) {
                //        if ($index) {
                //            $row->delete();
                //            Log::channel('same')->info('删除重复标题', [
                //                'id' => $row->id,
                //                'title' => $row->title,
                //            ]);
                //        }
                //    });
                //})->onQueue('default');
            }
        }

        $this->error('共删除'.$num);
    }
}
