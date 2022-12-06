<?php

namespace App\Console\Commands\Article;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExtractKeywords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:extract-keywords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '从文章表提取 Tabs/Keywords';

    /**
     * @return void
     */
    public function handle(): void
    {
        $tags = config('tags');
        $lastId = Article::where('checked', 1)
            ->where('has_train', 0)
            ->orderByDesc('id')
            ->value('id');
        $count = Article::count();

        $this->info('最大ID：'.$lastId);
        $this->comment('待清洗：'.$count);

        $bar = $this->output->createProgressBar($count);
        $star = 0;
        while ($star <= $lastId) {
            $list = Article::where('id', '>', $star)
                ->where('checked', 1)
                ->where('has_train', 0)
                ->take(20)
                ->get();
            $bar->advance($list->count());

            collect($list)->each(function ($row) use (&$star, $tags) {
                $star = $row->id;
                $subtable = 'articles_'.($row->id % 10);
                $content = DB::table($subtable)->where('id', $row->id)->value('content');
                $string = $row->title;
                $description = '';
                if ($content) {
                    $description = $this->formatContent($content);
                    $string .= strip_tags($content);
                }

                $tagsInfo = '';
                // 是否包含3层 Tags 信息
                foreach ($tags['sons'] as $key => $val) {
                    if (str()->contains($string, $key)) {
                        $tagsInfo = $val;
                        break;
                    }
                }

                // 第3层没得，看第2层有不
                if (empty($tagsInfo)) {
                    foreach ($tags['first'] as $key => $val) {
                        if (str()->contains($string, $key)) {
                            $tagsInfo = $val;
                            break;
                        }
                    }
                }
                $row->fill([
                    'description' => $description,
                    'has_train' => true,
                    'tags_info' => $tagsInfo,
                ]);
                $row->save();
            });
        }
    }

    public function formatContent($string): ?string
    {
        if ($string) {
            $string = preg_replace('/\s+/', '', trim($string));
            $string = preg_replace('/(<\/[p|div|section]>)/', "$1\n", $string);
            $string = preg_replace('/<(br\s|br)\/>/', "$1\n", $string);
            $words = explode(PHP_EOL, trim(strip_tags($string)));

            $content = '';
            foreach ($words as $word) {
                if (empty($word)) {
                    continue;
                }
                // -3 是 substr 得出的 。和 ！的值，不包含这两个就跳出
                if (!in_array(substr($word, -3), ['。', '！'])) {
                    continue;
                }
                if (strlen($content) > 200) {
                    break;
                }
                $content .= $word;
            }

            // 二次处理
            $string = preg_replace('/([。！])/u', "$1\n", $content);
            $words = explode(PHP_EOL, $string);
            $content = '';
            foreach ($words as $word) {
                if (strlen($content) > 100) {
                    break;
                }
                $content .= $word;
            }

            return $content;
        }

        return null;
    }
}
