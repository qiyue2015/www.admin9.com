<?php

namespace App\Jobs;

use App\Models\Article;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class InitCategoryIdJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $key = 'jcTPUIkefcCLgxQbF5DVz9By';

    protected string $secret = '6Kdm7fbNEOAeGqOzqCBV2UuEtbyLLjna';

    private Article $article;

    private function getToken()
    {
        return Cache::remember('baidu-ai-token', now()->addDays(30), function () {
            $url = 'https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id='.$this->key.'&client_secret='.$this->secret;
            return Http::get($url)->json('access_token');
        });
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Illuminate\Contracts\Redis\LimiterTimeoutException
     */
    public function handle()
    {
        Redis::funnel('key')->limit(5)->then(function () {
            $subtable = 'articles_'.($this->article->id % 10);
            $item = DB::table($subtable)->find($this->article->id);
            if ($item) {
                try {
                    $url = 'https://aip.baidubce.com/rpc/2.0/ai_custom/v1/text_cls/baike?access_token='.$this->getToken();
                    $response = Http::post($url, [
                        'text' => $this->article->title.PHP_EOL.strip_tags($item->content),
                        'threshold' => 0.6,
                    ]);
                    $results = $response->json('results');
                    $tags = collect($results)->sortByDesc('score')->map(function ($row) {
                        return $row['name'];
                    });
                    DB::table($subtable)->where('id', $this->article->id)->update([
                        'tags' => implode(',', $tags->toArray()),
                    ]);
                    $this->article->update(['category_id' => 1]);
                } catch (Exception $exception) {
                    $this->fail($exception);
                }
            }
        }, function () {
            $this->release(10);
        });
    }
}
