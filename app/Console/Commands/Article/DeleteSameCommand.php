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
        while ($star) {
            $this->info('正在执行（'.$star.'）');

            $list = Article::select(DB::raw('COUNT(title) as dd, title'))
                ->where('id', '>=', $star)
                ->groupBy('title')
                ->orderByDesc('dd')
                ->take(100)
                ->get();

            if ($list->isEmpty()) {
                $this->info('全部执行完毕.');
                break;
            }

            foreach ($list as $row) {
                $star = $row->id;
                if ($row->dd === 1) {
                    break 2;
                }

                echo $row->title."\t";
                //Article::whereTitle($row->title)->get(['id', 'title'])->each(function ($row, $index) {
                //    if ($index) {
                //        $row->delete();
                //        Log::channel('same')->info('删除重复标题', [
                //            'id' => $row->id,
                //            'title' => $row->title,
                //        ]);
                //    }
                //});
            }

            //collect($list)->each(function ($row) use ($bar, &$star) {
            //    $bar->advance();
            //    $star = $row->id;
            //
            //    if ($row->dd > 1) {
            //        Article::whereTitle($row->title)->get(['id', 'title'])->each(function ($row, $index) {
            //            if ($index) {
            //                $row->delete();
            //                Log::channel('same')->info('删除重复标题', [
            //                    'id' => $row->id,
            //                    'title' => $row->title,
            //                ]);
            //            }
            //        });
            //    }
            //});
        }
    }
}
