<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Overtrue\Pinyin\Pinyin;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            '国际', '时事', '军事', '社会', '体育', '财经', '科技', '娱乐', '情感', '汽车', '教育', '时尚',
            '游戏', '旅游', '美食', '文化', '搞笑', '家居', '动漫', '宠物', '历史', '音乐',
            '健康' => '健康养生',
            '母婴' => '母婴育儿',
            '星座' => '星座运势',
            '综合',
        ];
        $data = [];
        foreach ($items as $key => $val) {
            if (is_string($key)) {
                $data[] = [
                    'name' => $key,
                    'alias' => $val,
                    'slug' => Pinyin::permalink($key, ''),
                ];
            } else {
                $data[] = [
                    'name' => $val,
                    'alias' => $val,
                    'slug' => Pinyin::permalink($val, ''),
                ];
            }
        }

        Category::insert($data);
    }
}
