<?php

namespace App\Console\Commands\Init;

use App\Models\Archive;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Horizon\Tags;
use Overtrue\Pinyin\Pinyin;
use Storage;

class InitLongTailWordPack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:word-pack {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入长尾词包';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = $this->argument('url');
        $pathInfo = pathinfo($url);
        $path = 'word-packs/'.$pathInfo['basename'];
        if (!filter_var($url, FILTER_VALIDATE_URL) !== false) {
            $this->error('URL ERROR: '.$url);
        } elseif ($pathInfo['extension'] !== 'txt') {
            $this->error('只支持 txt 文本: '.$url);
        } else {
            try {
                $content = $this->downloadWordPack($url, $path);
                $content = trim($content);
                $list = explode(PHP_EOL, $content);
                $count = count($list);
                $bar = $this->output->createProgressBar($count);
                // 每次 500 条
                collect($list)->chunk(500)->each(function ($rows) use ($bar) {
                    $bar->advance($rows->count());
                    $data = [];
                    foreach ($rows as $val) {
                        $row = explode("\t", $val);
                        $data[] = [
                            'hash' => $row[0],
                            'title' => $row[2],
                            'tags' => $row[3].($row[4] !== 'NULL' ? ','.$row[4] : ''),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    Task::insert($data);
                });
            } catch (\Exception $exception) {
                Log::channel('word-pack')->error($exception->getMessage());
            }
        }
    }


    /**
     * 下载词包
     * @param $url
     * @param $path
     * @return string
     */
    public function downloadWordPack($url, $path): string
    {
        try {
            if (Storage::exists($path)) {
                return Storage::get($path);
            }

            return Http::get($url)->body();
        } catch (\Exception $exception) {
            Log::channel('word-pack')->error($exception->getMessage());
        }
    }
}
