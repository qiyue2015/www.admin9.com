<?php

namespace App\Console\Commands\Dongde;

use App\Models\Dongde;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DongdeSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dongde:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '从 Sitemap 拿到所有链接.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        ini_set('memory_limit', -1);

        for ($i = 1; $i <= 92; $i++) {
            $filename = 'sitemap/'.$i.'.xml';
            $this->info($filename);

            // 先存到本地
            //$url = 'https://m.idongde.com/sitemap/google/'.$i.'.xml';
            //$this->info($url);
            //$response = Http::get($url);
            //Storage::put($filename, $response->body());

            // 从本地再拿取放到数据库
            $content = Storage::get($filename);
            if (preg_match_all('/<loc>(.*?)<\/loc>/', $content, $matches)) {
                $data = [];
                foreach ($matches[1] as $url) {
                    if (str()->contains($url, '.shtml')) {
                        $filename = pathinfo($url, PATHINFO_FILENAME);
                        $data[] = [
                            'dongde_id' => 0,
                            'alias' => $filename,
                            'type' => '',
                            'category_id' => 0,
                            'channel_id' => 0,
                            'user_id' => 0,
                            'status' => false,
                            'title' => '',
                            'subtitle' => '',
                            'search_title' => '',
                            'toutiao_title' => '',
                            'sogou_title' => '',
                            'cover' => '',
                            'keywords' => '',
                            'tags' => '',
                            'description' => '',
                            'publish_at' => now(),
                            //'created_at' => '',
                            //'updated_at' => '',
                            'url' => $url,
                        ];
                    }
                }
                $bar = $this->output->createProgressBar(count($data));
                collect($data)->chunk(500)->each(function ($items) use ($bar) {
                    $bar->advance($items->count());
                    dispatch(static function () use ($items) {
                        Dongde::insert($items->toArray());
                    });
                });
            }
        }
    }
}
