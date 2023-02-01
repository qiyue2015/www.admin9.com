<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test {keyword}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle(): void
    {
        $keyword = $this->argument('keyword');

        $this->info($keyword);

        $list = Article::search($keyword)->take(200)->get();
        $data = [];
        foreach ($list as $row) {
            $data[] = [
                'id' => $row->id,
                'title' => $row->title,
            ];
        }

        $data[] = ['id' => '-', 'title' => count($list)];

        $this->table(['id' => 'ID', 'title' => '标题'], $data);

        //$list = Article::whereFullText('title', $keyword, ['mode' => 'boolean'])->take(10);
        //dd($list->get(['id', 'title'])->toArray());
        //$path = '/Users/fengqiyue/Documents/Project/caishengfeiyang/tv/videos';
        //$files = \File::files($path);
        //foreach ($files as $file) {
        //    $outFileName = str_replace(['videos', '.mp4'], ['images', '.jpg'], $file->getPathname());
        //    // 运行命令
        //    //$command = "ffmpeg -i ".$file->getPathname()." -y -f image2 -t 15 -s 3840x2160 ".$outFileName;
        //    $command = "ffmpeg -i {$file->getPathname()} -ss 00:00:15 -frames:v 1 ".$outFileName;
        //    $this->info($command);
        //    system($command);
        //}
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
