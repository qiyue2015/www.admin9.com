<?php

namespace App\Console\Commands\GenerateHtml;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateHtml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-html {type=home : 生成页面}';

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
    public function handle()
    {
        $type = $this->argument('type');
        if ($type === 'home') {
            $this->generateHome();
        } elseif ($type === 'sitemaps') {
            $this->generateSitemaps();
        } else {
            $this->generateArticles();
        }
    }

    /**
     * 生成首页.
     * @return void
     */
    private function generateHome(): void
    {
        $news = Article::checked()->orderByDesc('id')->take(90)->get();
        $string = view('welcome', compact('news'));
        $file = public_path('index.html');
        file_put_contents($file, $string);
    }

    /**
     * 批量生成文章
     * @return void
     */
    private function generateArticles(): void
    {
        //$optimus = app(Optimus::class);
        //Article::checked()
        //    ->take(1800)
        //    ->each(function ($article) use ($optimus) {
        //        $encode_id = $optimus->encode($article->id);
        //        $filename = $encode_id.'.html';
        //
        //        $dir = intdiv($encode_id, 1000000);
        //        $directories = public_path('views').'/'.$dir;
        //        $path = $directories.'/'.$filename;
        //        File::put($path, 'xxx');
        //        dd($article->id, $path, $filename);
        //        //$string = view('welcome', compact('news'));
        //        //$file = public_path('index.html');
        //        //file_put_contents($file, $string);
        //    });
    }

    private function generateSitemaps(): void
    {
        ini_set('memory_limit', -1);

        $star = 0;
        $i = 1;
        while (true) {
            $list = Article::checked()->where('id', '>', $star)
                ->take(50000)
                ->get(['id', 'created_at']);
            if ($list->isEmpty()) {
                break;
            }
            $filename = $i.'.xml';
            $this->info($filename);
            $xml = '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL;
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
            foreach ($list as $row) {
                $url = 'https://www.admin9.com'.$row->link();
                $lastmod = now()->parse($row->created_at)->format('Y-m-d');
                $xml .= "    <url>\n        <loc>{$url}</loc>\n        <lastmod>{$lastmod}</lastmod>\n    </url>\n";
            }
            $xml .= "\n</urlset>";

            Storage::put('public/sitemaps/'.$filename, $xml);

            $i++;
            $star = $list->last()->id;
        }
    }
}
