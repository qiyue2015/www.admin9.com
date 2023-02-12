<?php

namespace App\Jobs\Spider;

use App\Models\Photo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SpiderPixabayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $url;
    protected int $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url, $star)
    {
        $this->url = $url;
        $this->id = $star;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::get($this->url, [
            'key' => '32941141-1195135ce9ad88851011c66d1',
            'lang' => 'zh',
            'id' => $this->id,
        ]);

        if ($response->json('hits')) {
            $result = $response->json('hits.0');
            Photo::where('id', $this->id)->update([
                'status' => 2,
                'tags' => $result['tags'],
                'result' => $response->json(),
            ]);
        } elseif ($response->json('detail')) {
            //$this->release(10);
            $this->fail();
            Photo::where('id', $this->id)->update([
                'result' => $response->json('detail'),
            ]);
        } else {
            Photo::where('id', $this->id)->delete();
        }
    }
}
