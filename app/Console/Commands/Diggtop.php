<?php

namespace App\Console\Commands;

use App\Exceptions\FakeUserAgent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class Diggtop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'diggtop {--num=100 : 每次数数据}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function getProxy(): string
    {
        $proxies = [
            '106.12.96.17',
            '106.13.17.105',
            '182.61.40.144',
            '182.61.146.199',
            '182.61.42.155',
            '106.13.18.252',
            '106.13.17.113',
        ];
        $index = array_rand($proxies);
        return $proxies[$index];
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $num = (int) $this->option('num');
        $bar = $this->output->createProgressBar($num);
        $i = 0;
        while ($i < $num) {
            $i++;
            $bar->advance();
            $proxy = $this->getProxy();
            dispatch(static function () use ($proxy) {
                $url = 'https://live-play.vzan.com/api/topic/topic_config?tpId=92CCBBF8CE259423CE2EADA71630C119&isPcBrowser=true';
                $userAgent = FakeUserAgent::random();
                Http::timeout(30)
                    ->withoutVerifying()
                    //->withOptions(['proxy' => $proxy])
                    ->withHeaders(['User-Agent' => $userAgent])
                    ->get($url);
            });
        }
    }
}
