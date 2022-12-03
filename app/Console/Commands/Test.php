<?php

namespace App\Console\Commands;

use App\Models\Channel;
use App\Models\Train;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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
        $list = [
            '科技', '财经', '汽车', '游戏', '动漫',
            '时尚', '文化', '历史', '家居',
            '健康' => ['健康养生', '养生'],
            '星座' => ['星座运势', '运势'],
            '彩票',
            '母婴' => ['母婴育儿', '育儿'],
            '娱乐' => ['音乐'],
            '旅游', '教育', '体育', '美食', '情感', '宠物',
            '社会', '国际', '时事', '军事',
            '综合',
        ];
        collect($list)->each(function ($row, $key) {
            $channel = new Channel();
            if (is_numeric($key)) {
                $channel->name = $row;
            } else {
                $channel->name = $key;
                $channel->mapping = implode(',', $row);
            }
            $channel->save();
        });
        //$values = [];
        //$data = Train::all()->toArray();
        //foreach ($data as $row) {
        //    $tags = json_decode($row['lv1_categories']);
        //    foreach ($tags as $val) {
        //        $values[$val->tag] = $val->tag;
        //    }
        //}
        //dd($values);
    }
}
