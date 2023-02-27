<?php

namespace App\Console\Commands;

use App\Models\Archive;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
        $url = 'https://search5-search-lq.toutiaoapi.com/s/search_wenda/api/related_questions';
        $query = [
            'version_code' => '9.1.9',
            'app_name' => 'news_article',
            'app_version' => '9.1.9',
            'carrier_region' => 'CN',
            'device_id' => '31494770398360'.random_int(10, 99),
            'device_platform' => 'iphone',
            'enable_miaozhen_page' => 1,
            'enter_from' => 'search_result',
            'keyword' => '什么人打架抽什么烟',
        ];
        $response = Http::getWithProxy($url, $query);
        dd($response->body());
    }
}
