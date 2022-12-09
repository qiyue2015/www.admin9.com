<?php

namespace App\Jobs\Dataset;

use App\Models\Dataset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class DatasetBaiduBceValidJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Dataset $dataset;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Dataset $dataset)
    {
        $this->dataset = $dataset;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $url = 'https://aip.baidubce.com/rpc/2.0/nlp/v1/topic?access_token='.$this->getAccessToken().'&charset=UTF-8';
        $response = Http::timeout(30)
            ->asJson()
            ->post($url, [
                'content' => $this->dataset->desc.PHP_EOL.$this->dataset->answer,
                'title' => $this->dataset->title,
            ]);

        if ($response->json('item.lv1_tag_list.0.tag')) {
            $this->dataset->update([
                'status' => 1,
                'category' => $response->json('item.lv1_tag_list.0.tag'),
                'tags' => $response->body(),
            ]);
        }
    }

    /**
     * 获取token.
     * @return mixed
     */
    private function getAccessToken(): mixed
    {
        return cache()->remember('baidubce:token', now()->endOfMonth(), function () {
            $url = 'https://aip.baidubce.com/oauth/2.0/token?client_id=jcTPUIkefcCLgxQbF5DVz9By&client_secret=6Kdm7fbNEOAeGqOzqCBV2UuEtbyLLjna&grant_type=client_credentials';
            return Http::post($url)->json('access_token');
        });
    }
}
