<?php

namespace App\Jobs\Spider;

use App\Models\Archive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SpiderTouTiaoWenBaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Archive $archive;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Archive $archive)
    {
        $this->archive = $archive;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = 'https://search5-search-lq.toutiaoapi.com/s/search_wenda/api/related_questions';
        $query = [
            'version_code' => '9.1.9',
            'app_name' => 'news_article',
            'app_version' => '9.1.9',
            'carrier_region' => 'CN',
            'device_id' => '3149477039836078',
            'device_platform' => 'iphone',
            'enable_miaozhen_page' => 1,
            'enter_from' => 'search_result',
            'keyword' => $this->archive->title,
        ];
        $response = Http::getWithProxy($url, $query);
        $maps = collect($response->json('data'))->filter(function ($row) {
            return isset($row['display_type_self']); // 只需要问答的内容
        });

        $list = collect($maps)->map(function ($row) {
            return [
                'item_id' => $row['item_id'],
                'source' => $row['source'],
                'datetime' => $row['datetime'],
                'publish_time' => $row['publish_time'],
                'title' => $row['title'],
                'summary' => $row['display']['summary']['text'],
                'has_image' => $row['image_list'],
                'large_image_url' => $row['large_image_url'],
                'image_list' => $row['image_list'],
                'show_tag_list' => $row['show_tag_list'],
                'url' => $row['url'],
            ];
        })->toArray();
        if ($list) {
            $this->archive->update(['is_html' => true, 'is_wap_html' => true]);
            $this->archive->extend()->updateOrCreate(['display' => array_values($list)], ['id' => $this->archive->id]);
        } else {
            $this->archive->update(['is_html' => true]);
        }
    }
}