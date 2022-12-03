<?php

namespace App\Console\Commands\Train;

use App\Jobs\TrainTagsJob;
use App\Models\Article;
use App\Models\Train;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TrainTagsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'train:tags
                            {--channel_id : 频道ID}
                            {--num=100 : 每次清洗数量}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清理数据然后正式发布';

    public function handle()
    {
        $this->info('导入待清洗数据...');

        $num = (int) $this->option('num');
        $channel_id = (int) $this->option('channel_id');
        $count = Article::whereChannelId(0)->whereChecked(true)->count();
        $this->comment('频道['.$channel_id.']，每次执行['.$num.']，待处理['.$count.']');
        if ($count) {
            $list = Article::whereChannelId(0)->whereChecked(true)->take($num)->get();
            $bar = $this->output->createProgressBar($count);

            $channelIds = [];
            collect($list)->each(function ($article) use ($bar, &$channelIds) {
                $bar->advance();
                $train = [
                    'id' => $article->id,
                    'title' => $this->getTitle($article->title),
                    'content' => $this->getContent($article->id),
                    'status' => false,
                ];
                if (!Train::whereId($article->id)->exists() && $train = Train::query()->create($train)) {
                    // 临时修改一个频道ID
                    $article->update(['channel_id' => 999]);

                    // 进入队例
                    TrainTagsJob::dispatch($train, $article)->onQueue('just_for_train');
                }
            });
        }
    }

    /**
     * @return void
     */
    public function _handle(): void
    {
        $num = (int) $this->option('num');
        $channel_id = (int) $this->option('channel_id');
        $this->info('导入待清洗数据...');
        $this->comment('频道ID：'.$channel_id.'，每次执行 '.$num);

        // 最大ID
        $lastId = 0;

        // 待处理
        $count = Article::where('id', '>', $lastId)
            ->where('checked', true)
            ->where('channel_id', 0)
            ->count();

        $this->info('待清理数据：'.$count);

        $bar = $this->output->createProgressBar($count);

        // 开始
        $star = 1;
        while ($star) {
            $list = Article::where('id', '>', $lastId)
                ->where('checked', true)
                ->take($num)
                ->get();

            if (!$list->isEmpty()) {
                $bar->advance($list->count());
                $channelIds = [];
                foreach ($list as $article) {
                    $lastId = $article->id;
                    $train = [
                        'id' => $article->id,
                        'title' => $this->getTitle($article->title),
                        'content' => $this->getContent($article->id),
                        'status' => false,
                    ];
                    if (!Train::whereId($article->id)->exists() && Train::query()->create($train)) {
                        $channelIds[] = $article->id;
                        TrainTagsJob::dispatch($article)->onQueue('just_for_train');
                    }
                }

                if ($channelIds) {
                    Article::whereIn('id', $channelIds)->update(['channel_id' => 999]);
                }
            } else {
                $star = 0;
            }
        }

        $this->info('all ok.');
    }

    public function getTitle($title)
    {
        return str()->limit($title, 50);
    }

    public function getContent($id): ?string
    {
        $subtable = 'articles_'.$id % 10;
        $content = DB::table($subtable)->where('id', $id)->value('content');
        return $this->formatContent($content);
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
                if (strlen($content) > 500) {
                    break;
                }
                $content .= $word;
            }

            return $content;
        }

        return null;
    }
}
