<?php

namespace App\Console\Commands;

use App\Models\Archive;
use App\Models\Category;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

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
        $list = Category::where('num', '>', 0)->get()->toArray();
        collect($list)->each(function ($category) {
            $this->comment($category['name']);
            $list = Archive::whereCategoryId($category['id'])->limit(200)->get(['id', 'title'])->toArray();
            $homeRandomKey = array_rand($list);
            $homeId = $list[$homeRandomKey]['id'];
            Archive::whereId($homeId)->update(['flag' => 'c']);

            $ids = [];
            $categoryRandomKeys = array_rand($list, 10);
            foreach ($categoryRandomKeys as $val) {
                $ids[] = $list[$val]['id'];
            }
            Archive::whereIn('id', $ids)->update(['flag' => 'h']);
        });

    }
}
