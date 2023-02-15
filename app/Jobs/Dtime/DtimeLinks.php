<?php

namespace App\Jobs\Dtime;

use App\Models\Dtime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class DtimeLinks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected bool $local;
    protected string $path;
    protected string $urlHash;

    /**
     * DtimeLinks
     * @return array
     */
    public function tags(): array
    {
        return ['links'];
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path, $local)
    {
        $this->path = $path;
        $this->urlHash = md5($path);
        $this->local = $local;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = 'https://www.dtime.com'.$this->path;
        $response = Http::get($url);

        if (!$this->local) {
            Dtime::whereUrlHash($this->urlHash)->update(['status' => true]);
        }

        if (preg_match_all('/\/a\/([\d]+)-([0-9a-zA-Z]+)\.html/i', $response->body(), $matches)) {
            $links = [];
            foreach ($matches[0] as $link) {
                $urlHash = md5($link);
                if (!$this->checkLink($urlHash)) {
                    $links[] = [
                        'title' => '',
                        'url' => $link,
                        'url_hash' => $urlHash,
                        'status' => false,
                    ];
                    cache()->forever('dtime:link:'.$urlHash, true);
                }
            }
            if ($links) {
                Dtime::insert($links);
            }
        }
    }

    private function checkLink($urlHash)
    {
        return cache()->rememberForever('dtime:link:'.$urlHash, function () use ($urlHash) {
            return Dtime::whereUrlHash($urlHash)->exists();
        });
    }
}
