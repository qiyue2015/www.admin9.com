<?php

namespace App\Jobs;

use App\Models\Dataset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler;

class TrainTagsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Dataset $dataset;

    public function __construct(Dataset $dataset)
    {
        $this->dataset = $dataset;
    }

    /**
     * @throws \JsonException
     */
    public function handle()
    {
        try {
            $response = Http::getWithProxy($this->dataset->link);
            $category2 = '';
            if (preg_match_all('/<a href="#!" data-cateid="[\d]+">(.*?)<\/a>/u', $response->body(), $categoryMatches) && count($categoryMatches[1]) === 3) {
                $category2 = $categoryMatches[1][2];
            }

            $crawler = new Crawler();
            $crawler->addHtmlContent($response->body());
            $stepsForCatalog = $crawler->filter('.guide-detail>li>p')->each(function (Crawler $cr) {
                return $cr->text();
            });

            $multiSteps = $crawler->filter('.guide-detail>li')->each(function (Crawler $cr) {
                $text = $cr->filter('.guide-content')->each(function (Crawler $c) {
                    return '<p>'.$c->text().'</p>';
                });

                $imgs = $cr->filter('img')->each(function (Crawler $c) {
                    return '<p><img src="'.$c->attr('data-src').'" alt=""></p>';
                });

                return implode(PHP_EOL, $text).implode(PHP_EOL, $imgs);
            });

            $data = [
                'status' => 1,
                'category2' => $category2,
                //'created_at' => now()->parse($timestamp)->toDateTimeString(),
                'content' => implode(PHP_EOL, $multiSteps),
                'body' => implode(PHP_EOL, $stepsForCatalog),
            ];
            $this->dataset->fill($data)->save();
        } catch (RuntimeException $exception) {
            $this->fail($exception);
        }
    }

    public function clearBom($str): array|string
    {
        if (0 === strpos(bin2hex($str), 'efbbbf')) {
            return substr($str, 3);
        }
        return $str;
    }

}
