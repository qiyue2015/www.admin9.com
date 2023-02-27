<?php

namespace App\Jobs\Init;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InitLongTailWordPackJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $item;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($string)
    {
        $this->item = explode("\t", $string);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!Task::where('hash', $this->item[0])->exists()) {
            Task::create([
                'hash' => $this->item[0],
                'title' => $this->item[2],
                'tags' => $this->item[3].($this->item[4] !== 'NULL' ? ','.$this->item[4] : ''),
            ]);
        }
    }
}
