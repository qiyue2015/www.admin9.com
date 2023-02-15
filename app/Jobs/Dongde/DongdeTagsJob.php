<?php

namespace App\Jobs\Dongde;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DongdeTagsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $tagsId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tagsId)
    {
        $this->tagsId = $tagsId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        for ($p = 1; $p <= 10; $p++) {
            $dir = (int) ceil($this->tagsId / 10000);
            $filename = $this->tagsId.'-'.$p.'.json';
            $path = 'dongde/tags/'.$dir.'/'.$filename;

            // 文件存在则跳出此次循环
            if (Storage::exists($path)) {
                continue;
            }

            // 通过远程获取
            $url = 'https://m.idongde.com/t/'.$this->tagsId.'/page?page='.$p;
            $response = Http::get($url);
            if (!$response->json('data.data')) {
                break; // 空的跳出
            }

            // 写入文件
            Storage::put($path, $response->body());
        }
    }
}
