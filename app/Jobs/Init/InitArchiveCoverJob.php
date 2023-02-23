<?php

namespace App\Jobs\Init;

use App\Models\Archive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\DomCrawler\Crawler;

class InitArchiveCoverJob implements ShouldQueue
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
        $content = $this->archive->extend->content;
        $crawler = new Crawler();
        $crawler->addHtmlContent($content);
        $images = $crawler->filter('img')->each(function (Crawler $cr) {
            return $cr->attr('src');
        });
        if (!empty($images)) {
            $newImages = [];
            $i = 1;
            foreach ($images as $src) {
                $path = parse_url($src, PHP_URL_PATH);
                if (str()->contains($path, '/d/file/')) {
                    $newImages[] = str_replace('/d/', '/', $path);
                } else {
                    $dir = now()->parse($this->archive->created_at)->format('Ymd');
                    $newImages[] = '/file/'.$dir.'/'.$this->archive->id.'-'.$i.'.jpg';
                }
                $i++;
            }

            $content = str_replace($images, $newImages, $content);

            $this->archive->update(['has_cover' => true, 'cover' => $newImages[0]]);
            $this->archive->extend()->update([
                'content' => $content,
            ]);
        }
    }
}
