<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\Channel;
use App\Models\Train;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class TrainTagsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Train $train;

    protected Article $article;

    protected string $appid = '1224070';

    protected string $secret = '026a8f6cc2624b808254227f088e9f32';

    /**
     * @param  Train  $train
     */
    public function __construct(Train $train, Article $article)
    {
        $this->train = $train;
        $this->article = $article;
    }

    public function handle()
    {
        $tags = $this->getCategories($this->train->title, $this->train->content);
        $scoreArr = array_column($tags['lv1_tag_list'], 'score'); // 处理一级
        array_multisort($scoreArr, SORT_DESC, $tags['lv1_tag_list']);

        // 一级标签用来处理为频道
        $lv1 = head($tags['lv1_tag_list']);
        $channel = cache()->rememberForever('channel:'.$lv1['tag'], function () use ($lv1) {
            return Channel::whereName($lv1['tag'])->first();
        });

        // 二级 tags 暂时存储
        $keyboard = [];
        collect($tags['lv2_tag_list'])->map(function ($row) use ($lv1, &$keyboard) {
            if ($row['tag'] !== $lv1['tag']) {
                $keyboard[] = $row;
            }
        });

        // 重新去拉取文章标签
        $newTags = [];
        $tagsResult = $this->getTags($this->train->title, $this->train->content);
        foreach ($tagsResult as $key => $newTag) {
            if ($key >= 4) {
                continue;
            }
            $newTags[] = $newTag['tag'];
        }
        $newTags = array_unique($newTags);

        //$this->train->lv1_categories = $channel->name;
        //$this->train->lv2_categories = json_encode($keyboard, JSON_UNESCAPED_UNICODE);
        //$this->train->tags = json_encode($newTags, JSON_UNESCAPED_UNICODE);
        //$this->train->save();

        $this->article->channel_id = $channel->id;
        $this->article->keyboard = $newTags ? implode(',', $newTags) : '';
        $this->article->save();
    }

    /**
     * 提取分类
     * @param $title
     * @param $content
     * @return array|mixed
     */
    private function getCategories($title, $content)
    {
        $url = 'https://route.showapi.com/1750-7';
        $data = [
            'showapi_appid' => $this->appid,
            'showapi_sign' => $this->secret,
            'title' => $title,
            'content' => $content,
        ];

        $response = Http::timeout(30)->withoutVerifying()->asForm()->post($url, $data);
        if ($response->json('showapi_res_code') === 0) {
            return $response->json('showapi_res_body.data.item');
        }

        throw new RuntimeException($response->json('showapi_res_error'));
    }

    /**
     * 提取标签
     */
    private function getTags(string $title, string $content)
    {
        $url = 'https://route.showapi.com/1750-5';
        $data = [
            'showapi_appid' => $this->appid,
            'showapi_sign' => $this->secret,
            'title' => $title,
            'content' => $content,
        ];

        $response = Http::timeout(30)->withoutVerifying()->asForm()->post($url, $data);
        if ($response->json('showapi_res_code') === 0) {
            return $response->json('showapi_res_body.data.items');
        }

        throw new RuntimeException($response->json('showapi_res_error'));
    }
}
