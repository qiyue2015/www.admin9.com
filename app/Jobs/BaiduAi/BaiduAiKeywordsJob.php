<?php

namespace App\Jobs\BaiduAi;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class BaiduAiKeywordsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Article $article;
    protected string $content;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Article $article, string $content)
    {
        $this->article = $article;
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cookies = [
            'user_device_id' => 'aeda85d0eaf14c07b227c916a0c95d3f',
            'user_device_id_timestamp' => now()->timestamp,
            'PHPSESSID' => 'oge5i0avrr1riecrv6jc5sejtq',
            'uid' => 44776,
            'token' => 'b794cbfb-045a-4ac4-be10-6f8cc7af4c8f',
        ];

        $url = 'https://www.meixiaosan.com/getkeywords.html';
        $response = Http::withoutVerifying()
            ->timeout(20)
            ->withCookies($cookies, '.meixiaosan.com')
            ->withHeaders(['x-requested-with' => 'XMLHttpRequest'])
            ->post($url, [
                'title' => $this->article->title,
                'content' => $this->content,
            ]);

        $keywords = $response->json('data.newtext');
        if ($keywords) {
            $this->article->increment('status', 3, [
                'keywords' => $keywords,
            ]);
        }
    }
}
