<?php

namespace App\Jobs\Init;

use App\Models\Archive;
use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ArchiveCategoryJob implements ShouldQueue
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
        $tags = $this->archive->tags;
        if ($tags) {
            $tags = array_filter($tags);
            $tags = array_unique($tags);
            $key = md5($tags[0]);
            $category = \Cache::remember('category:'.$key, now()->addHours(3), function () use ($tags) {
                return Category::where('alias', $tags[0])->firstOrFail();
            });
            $this->archive->update(['tags' => implode(',', $tags), 'category_id' => $category->id]);
        }
    }
}
