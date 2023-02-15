<?php

namespace App\Console\Commands\Dongde;

use App\Models\Dongde;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DongdeCategoriesInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dongde:categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '拉取各个分类的例表信息';

    protected array $categoryIds = [
        24, 25, 26, 27, 28, 29, 31, 33, 34, 35, 36, 37, 38, 39, 41, 43, 45, 46, 48, 50, 52, 55, 57, 59, 60,
        61, 63, 64, 73, 74, 76, 81, 87, 93, 99, 100, 101, 102, 103, 105, 106, 107, 108, 110, 113, 114, 116,
        117, 118, 119, 120, 121, 125, 126, 128, 129, 130, 131, 132, 133, 134, 135, 136, 137, 138, 139, 140,
        141, 142, 143, 144, 145, 146,
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        collect($this->categoryIds)->each(function ($categoryId) {
            for ($i = 50; $i <= 100; $i++) {
                $url = 'https://m.idongde.com/category/'.$categoryId.'/page?page='.$i.'&l=20';
                $this->info($url);

                dispatch(static function () use ($url, $categoryId, $i) {
                    $path = 'dongde/categories/'.$categoryId.'-'.$i.'.json';

                    // 文件存在不再往下执行
                    if (!Storage::exists($path)) {
                        $response = Http::get($url);

                        // 写入一份到本地
                        Storage::put($path, $response->body());

                        // 遍历入库
                        collect($response->json('data.data'))->each(function ($item) {
                            $tags = collect($item['tags'])->map(function ($val) {
                                return $val['name'];
                            });

                            $data = [
                                'type' => $item['type'],
                                'status' => 2,
                                'category_id' => $item['category_id'],
                                'channel_id' => $item['channel_id'],
                                'title' => $item['title'],
                                'subtitle' => $item['subtitle'],
                                'search_title' => $item['search_title'],
                                'toutiao_title' => $item['toutiao_title'],
                                'sogou_title' => $item['sogou_title'],
                                'keywords' => $item['keywords'] ?: '',
                                'tags' => implode(',', $tags->toArray()),
                                'description' => $item['description'],
                                'cover' => $item['cover'],
                                'publish_at' => now()->parse($item['publish_time_name']),
                                'created_at' => now()->parse($item['create_time_name']),
                                'updated_at' => now()->parse($item['update_time_name']),
                                'dongde_id' => $item['id'],
                            ];

                            Dongde::where('alias', $item['alias'])
                                ->where('status', '<', 2)
                                ->update($data);
                        });
                    }
                });
            }
        });
    }
}
