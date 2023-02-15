<?php

namespace App\Console\Commands\Dongde;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DongdeTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dongde:tags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '拿到 TAGS 例表信息';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', -1);
        $bar = $this->output->createProgressBar(100000);
        for ($i = 100000; $i < 200000; $i++) {
            $bar->advance();

            // 队例执行
            dispatch(static function () use ($i) {
                for ($p = 1; $p <= 10; $p++) {
                    $dir = (int) ceil($i / 10000);
                    $filename = $i.'-'.$p.'.json';
                    $path = 'dongde/tags/'.$dir.'/'.$filename;

                    // 文件存在则跳出此次循环
                    if (Storage::exists($path)) {
                        continue;
                    }

                    // 通过远程获取
                    $url = 'https://m.idongde.com/t/'.$i.'/page?page='.$p;
                    $response = Http::get($url);
                    if (!$response->json('data.data')) {
                        break; // 空的跳出
                    }

                    // 写入文件
                    Storage::put($path, $response->body());
                }
            });
        }
    }
}
