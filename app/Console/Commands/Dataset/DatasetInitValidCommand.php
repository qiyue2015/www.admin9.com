<?php

namespace App\Console\Commands\Dataset;

use App\Exceptions\FakeUserAgent;
use App\Models\Dataset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DatasetInitValidCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dataset:init-valid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '采集验证集';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit', -1);

        // 百度经验分类
        $ids = [1, 10, 37, 50, 73, 86, 93, 101, 108, 123];
        $cname = [
            1 => '美食营养', 10 => '游戏数码', 37 => '手工爱好', 50 => '生活家居',
            73 => '健康养生', 86 => '运动户外', 93 => '职场理财', 101 => '情感交际',
            108 => '母婴教育', 123 => '时尚美容',
        ];
        collect($ids)->each(function ($cid) use ($cname) {
            $this->comment('正在执行（'.$cid.'）');
            dispatch(static function () use ($cname, $cid) {
                $url = 'https://jingyan.baidu.com/ajax/home/getcolumn?cid='.$cid;
                $response = Http::withoutVerifying()->withUserAgent(FakeUserAgent::random())->get($url);
                if ($response->ok()) {
                    $list = $response->json('data.specialColumn.list');
                    foreach ($list as $row) {
                        $bcid = $response->json('data.specialColumn.cid');
                        $data = [
                            'type' => 'valid',
                            'category1' => $cname[$bcid] ?: $bcid,
                            'category2' => $row['cid'],
                            'tags' => '',
                            'title' => $row['title'],
                            'desc' => $row['brief'],
                            'body' => '',
                            'link' => 'https://jingyan.baidu.com/article/'.$row['eidEnc'].'.html',
                            'status' => 0,
                        ];

                        $checkTitleExists = Dataset::where('title', $data['title'])->where('category1', $data['category1'])->doesntExist();
                        if ($checkTitleExists) {
                            Dataset::insert($data);
                        }
                    }
                }
            });
        });
    }

    public function checkTitleExists($category, $title)
    {
        $key = md5($category.$title);
        return cache()->rememberForever($key, function () use ($category, $title) {
            return Dataset::where('title', $title)->where('category1', $category)->exists();
        });
    }

    public function formatContent($string): ?string
    {
        if (!$string) {
            return '';
        }

        $string = strip_tags($string);
        $string = preg_replace('/\s+/', '', trim($string));
        $string = preg_replace("/([。!\?])/u", "$1###", $string);
        $words = explode('###', $string);
        $content = '';
        foreach ($words as $word) {
            if (strlen($content) > 500) {
                break;
            }
            $content .= $word;
        }

        return $content;
    }
}
