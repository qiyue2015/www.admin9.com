<?php

namespace App\Console\Commands\Init;

use App\Models\Archive;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
                        $category = $this->getCategory($row[3]);
                        $data[] = [
                            'category_id' => $category->id,
                            'title' => $row[2],
                            'tags' => $row[3].($row[4] !== 'NULL' ? ','.$row[4] : ''),
                        ];
                    }
                    Archive::insert($data);
                });
            } catch (\Exception $exception) {
                Log::channel('download-word-pack')->error($exception->getMessage());
            }
        }
    }

    public function getCategory($string)
    {
        if ($string) {
            $key = 'category:'.md5($string);
            return cache()->remember($key, now()->addHour(), function () use ($string) {
                $category = Category::whereAlias('name', $string)->first();
                if ($category) {
                    return $category;
                }
                $slug = Pinyin::permalink($string, '');
                return Category::firstOrCreate(['alias' => $string], ['name' => $string, 'slug' => $slug]);
            });
        }

        return $this->getCategory('其它');
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
            Log::channel('download-word-pack')->error($exception->getMessage());
        }
    }
}
