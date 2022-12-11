<?php

namespace App\Console\Commands\Dataset;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatasetInitTrainCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dataset:init-train';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入训练集';

    public function handle(): void
    {
        ini_set('memory_limit', -1);

        $this->comment('导入 Articles 表 数据...');

        $star = 0;
        $lastId = Article::checked()->max('id');
        $count = Article::checked()->count();
        $bar = $this->output->createProgressBar($count);

        while ($star < $lastId) {
            $bar->advance();

            dispatch(static function () use ($star) {
                $list = Article::checked()->where('id', '>', $star)->take(1000)->get();
                collect($list)->each(function ($article) use (&$star, &$txt) {
                    $star = $article->id;

                    $txt = $article->title;
                    $content = DB::table('articles_'.($article->id % 10))->where('id', $article->id)->value('content');

                    if (!is_null($content) && $content = strip_tags($content)) {
                        $rows = explode(PHP_EOL, $content);

                        $string = '';
                        foreach ($rows as $row) {
                            if (mb_strwidth($string) > 300) {
                                return $string;
                            }
                            $string .= trim($row);
                        }

                        if ($string) {
                            $txt .= PHP_EOL.$string;
                            $path = 'articles-baidu-bce/'.($article->id % 1000).'/'.$article->id.'.txt';
                            Storage::put($path, $txt);
                        }
                    }
                });
            })->onQueue('just_for_train');
        }
    }
}
