<?php

namespace App\Console\Commands;

use App\Models\Archive;
use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

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
        $lastId = DB::table('archive_index')->orderByDesc('id')->value('id');
        $lastId = (int) $lastId;
        $count = Article::where('id', '>', $lastId)->count();
        $bar = $this->output->createProgressBar($count);

        $star = 1;
        while ($star) {
            $list = Article::where('id', '>', $lastId)->take(1000)->get();
            if (!$list->isEmpty()) {
                $bar->advance($list->count());

                $data = [];
                $index = [];
                foreach ($list as $article) {
                    $lastId = $article->id;

                    $index[] = [
                        'id' => $article->id,
                        'channel_id' => $article->channel_id,
                        'category_id' => $article->category_id,
                        'checked' => $article->checked,
                        'publish_at' => $article->created_at ?: $article->updated_at,
                    ];

                    $data[] = [
                        'id' => $article->id,
                        'channel_id' => $article->channel_id,
                        'category_id' => $article->category_id,
                        'title' => $article->title,
                        'short_title' => '',
                        'flag' => '',
                        'thumbnail' => '',
                        'source_name' => '',
                        'author_name' => '',
                        'description' => '',
                        'filename' => '',
                        'keywords' => $article->keyboard ?: '',
                        'checked' => $article->checked,
                        'created_at' => $article->created_at ?: $article->updated_at,
                        'updated_at' => $article->updated_at,
                    ];
                }

                DB::table('archive_index')->insert($index);
                Archive::insert($data);
            } else {
                $star = 0;
            }
        }
    }


    /**
     * 对整数id进行可逆混淆
     *
     * @param $id
     * @return int
     */
    public static function encodeId($id): int
    {
        $sid = ($id & 0xff000000);
        $sid += ($id & 0x0000ff00) << 8;
        $sid += ($id & 0x00ff0000) >> 8;
        $sid += ($id & 0x0000000f) << 4;
        $sid += ($id & 0x000000f0) >> 4;
        $sid ^= 10;
        return $sid;
    }

    /**
     * 对通过encodeId混淆的id进行还原
     *
     * @param $sid
     * @return false|int
     */
    public static function decodeId($sid): bool|int
    {
        if (!is_numeric($sid)) {
            return false;
        }
        $sid ^= 10;
        $id = ($sid & 0xff000000);
        $id += ($sid & 0x00ff0000) >> 8;
        $id += ($sid & 0x0000ff00) << 8;
        $id += ($sid & 0x000000f0) >> 4;
        $id += ($sid & 0x0000000f) << 4;
        return $id;
    }
}
