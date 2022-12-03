<?php

use App\Models\Channel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $list = [
            '娱乐', '财经', '科技', '国际', '军事', '文化', '教育',
            '健康' => ['健康养生', '养生'],
            '汽车',
            '时尚',
            '星座' => ['星座运势', '运势'],
            '游戏', '体育', '理财', '历史', '证劵',
            '母婴' => ['母婴育儿', '育儿'],
            '数码', '家居',
            '社会', '旅游', '时事', '动漫', '音乐', '美食', '情感', '宠物',
            '综合',
        ];
        collect($list)->each(function ($row, $key) {
            if (is_numeric($key)) {
                Channel::updateOrInsert(['name' => $row]);
            } else {
                Channel::updateOrInsert(['name' => $key, 'mapping' => implode(',', $row)]);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Channel::truncate();
    }
};
