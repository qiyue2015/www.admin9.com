<?php

namespace App\Console\Commands\Init;

use App\Ace\Horizon\CustomQueue;
use App\Jobs\Init\InitLongTailWordPackJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
     * @return void
     */
    public function handle(): void
    {
        $url = $this->argument('url');
        $pathInfo = pathinfo($url);
        $path = 'word-packs/'.$pathInfo['basename'];
        if (!filter_var($url, FILTER_VALIDATE_URL) !== false) {
            $this->error('URL ERROR: '.$url);
        } elseif ($pathInfo['extension'] !== 'txt') {
            $this->error('只支持 txt 文本: '.$url);
        } else {
            $content = $this->downloadWordPack($url, $path);
            $content = trim($content);
            $list = explode(PHP_EOL, $content);
            $count = count($list);
            $bar = $this->output->createProgressBar($count);
            // 每次 500 条
            collect($list)->chunk(500)->each(function ($rows) use ($bar) {
                $bar->advance($rows->count());
                foreach ($rows as $val) {
                    InitLongTailWordPackJob::dispatch($val)->onQueue(CustomQueue::LARGE_PROCESSES_QUEUE);
                }
            });
        }
    }


    /**
     * 下载词包
     * 
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
