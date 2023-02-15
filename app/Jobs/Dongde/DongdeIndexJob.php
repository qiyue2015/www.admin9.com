<?php

namespace App\Jobs\Dongde;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DongdeIndexJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $star;
    private string $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($star, $path)
    {
        $this->star = $star;
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = 'https://m.idongde.com/page?o='.$this->star.'&l=20';
        $response = Http::get($url);
        // 如果 API 返回有数据，则将返回的数据写入文件
        if ($response->json('data.data')) {
            Storage::put($this->path, $response->body());
        } else {
            // 否则，将错误信息写入日志
            Log::error($this->path, ['body' => $response->body()]);
        }
    }
}
