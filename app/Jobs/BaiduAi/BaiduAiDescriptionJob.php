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

class BaiduAiDescriptionJob implements ShouldQueue
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
        if ($this->article->title && $this->content) {
            try {
                $url = 'https://aip.baidubce.com/rpc/2.0/nlp/v1/news_summary?access_token=&charset=UTF-8&access_token='.$this->getToken();
                $data = [
                    'title' => Str::limit($this->article->title, 40, ''),
                    'content' => Str::limit($this->content, 1000),
                    'max_summary_len' => 150,
                ];
                $response = Http::asJson()->post($url, $data);
                $result = $response->object();
                if (isset($result->error_code)) {
                    // token 过期
                    if ($result->error_code === 110) {
                        cache()->delete('baidu-ai-token');
                        $this->release(10);
                    } else {
                        Log::error('[提取摘要]'.$result->error_msg, $data);
                        throw new \RuntimeException($response->body());
                    }
                } else {
                    $this->article->increment('status', 2, [
                        'description' => $result->summary,
                    ]);
                }
            } catch (\Exception $exception) {
                $this->fail($exception);
            }
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
