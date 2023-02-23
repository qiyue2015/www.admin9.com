<?php

namespace App\Console\Commands\Init;

use App\Models\Archive;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class InitArchiveCibao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:cibao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        $content = Storage::get('cibao/20230221_fmUCYtBAD7.txt');
        $list = explode(PHP_EOL, $content);
        collect($list)->chunk(10)->each(function ($items, $index) {
            $this->info($index);
            dispatch(function () use ($items) {
                $data = [];
                foreach ($items as $row) {
                    $row = explode("\t", $row);
                    $tags = trim($row[3]);
                    if ($row[4] !== 'NULL') {
                        $tags .= ','.trim($row[4]);
                    }
                    $data[] = [
                        'title' => trim($row[2]),
                        'tags' => $tags,
                        'baidu_id' => trim($row[0]),
                    ];
                }
                Archive::insert($data);
            });
        });
    }
}
