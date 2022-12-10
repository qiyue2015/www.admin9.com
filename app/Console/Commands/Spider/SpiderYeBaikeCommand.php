<?php

namespace App\Console\Commands\Spider;

use App\Exceptions\FakeUserAgent;
use App\Jobs\Spider\SpiderYeBaikeJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class SpiderYeBaikeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:yebaike';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $url = 'https://www.yebaike.com/e/web/?type=rss2';
        $response = Http::withoutVerifying()->withUserAgent(FakeUserAgent::random())->get($url);
        $this->comment('开始请求：'.$url);
        if (preg_match_all('/<link>(.*?)<\/link>/', $response->body(), $matches)) {
            $bar = $this->output->createProgressBar(count($matches[1]));
            collect($matches[1])->each(function ($link) use ($bar) {
                $bar->advance();
                if (str()->contains($link, '.html')) {
                    $link = str_replace('www.', 'm.', $link);
                    $key = md5($link);
                    if (!Cache::get($key)) {
                        Cache::forever($key, $link);
                        SpiderYeBaikeJob::dispatch($link)->onQueue('just_for_article');
                    }
                }
            });
        }
    }
}
