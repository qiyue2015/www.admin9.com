<?php

namespace App\Console\Commands\Init;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function Clue\StreamFilter\fun;

class InitArticleDescriptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:article-description';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $count = Article::count();
        $maxId = Article::max('id');
        $star = 0;
        $bar = $this->output->createProgressBar($count);
        while ($star < $maxId) {
            $list = Article::where('id', '>', $star)->take(500)->get();
            $bar->advance($list->count());
            foreach ($list as $row) {
                $star = $row->id;

                if ($row->description) {
                    continue;
                }

                dispatch(function () use ($row) {
                    // 取副表内容
                    $subTable = 'articles_'.($row->id % 10);
                    $sub = DB::table($subTable)->where('id', $row->id)->first();
                    $content = trim(strip_tags($sub->content));
                    if (is_null($content)) {
                        return;
                    }

                    $description = Str::limit($content, 300);
                    if (preg_match_all('/(.*?)([。！……])(?![”"」】\]\)）》>])/u', $description, $matches)) {
                        $description = implode('', $matches[0]);
                    }

                    $row->description = $description;
                    $row->save();
                });
            }
        }
    }
}
