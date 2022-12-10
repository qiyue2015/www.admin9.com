<?php

namespace App\Console\Commands\Spider;

use App\Exceptions\FakeUserAgent;
use App\Jobs\Spider\SpiderYeBaikeJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        $url = 'https://m.yebaike.com/e/web/?type=rss2';
        $response = Http::withoutVerifying()->withUserAgent(FakeUserAgent::random())->get($url);
        $this->comment('开始请求：'.$url);

        Log::channel('spider')->error('请求 rss2');

        if (preg_match_all('/<link>(.*?)<\/link>/', $response->body(), $matches)) {
            $bar = $this->output->createProgressBar(count($matches[1]));
            collect($matches[1])->each(function ($link) use ($bar) {
                $bar->advance();
                if (str()->contains($link, '.html')) {
                    $key = 'spider:'.md5($link);
                    if (!Cache::get($key)) {
                        SpiderYeBaikeJob::dispatch($link)->onQueue('just_for_article');
                        Cache::forever($key, $link);
                    }
                } else {
                    Log::channel('spider')->error($link);
                }
            });
        } else {
            Log::channel('spider')->error('未获得相关内容', ['content' => $response->body()]);
        }

        Log::channel('spider')->error(PHP_EOL.' -------------- End -------------- '.PHP_EOL);
    }
}
