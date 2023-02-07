<?php

namespace App\Jobs\BaiduAi;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BaiduAiKeywordsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $content;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = 'https://aip.baidubce.com/rpc/2.0/nlp/v1/txt_keywords_extraction?access_token='.$this->getToken();
        $data = [
            'text' => Str::limit($this->content, 2000),
            'num' => 3,
        ];
        $response = Http::asJson()->post($url, $data);
        $result = $response->json();
        echo Str::limit($this->content, 2000);
        dd($result, $this->getToken());

        Log::debug($response->body());
        $keywords = $response->json('data.newtext');
        if ($keywords) {
            $this->article->increment('status', 3, [
                'keywords' => $keywords,
            ]);
        }
    }


    /**
     * 获取百度TOKEN
     *
     * @return mixed
     */
    private
    function getToken(): mixed
    {
        return Cache::remember('baidu-ai-token', now()->addDays(30), static function () {
            $url = 'https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id='.config('baidu-ai.nlp.key').'&client_secret='.config('baidu-ai.nlp.secret');
            return Http::get($url)->json('access_token');
        });
    }
}
