<?php

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
        Schema::create('archive_index', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('channel_id')->comment('频道ID');
            $table->unsignedSmallInteger('category_id')->comment('分类ID');
            $table->boolean('checked')->comment('已审核');
            $table->timestamp('publish_at')->comment('发布时间');
            $table->timestamps();

            $table->index('channel_id');
            $table->index('category_id');
            $table->index('checked');
            $table->index('publish_at');

            $table->index(['created_at', 'id']);
            $table->index(['channel_id', 'category_id', 'created_at', 'checked', 'id']);
        });

        Schema::create('archives', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedSmallInteger('channel_id')->comment('频道ID');
            $table->unsignedSmallInteger('category_id')->comment('分类ID');
            $table->string('title', 100)->comment('标题');
            $table->char('short_title', 36)->comment('短标题');
            $table->set('flag', ['h', 'c', 'f', 'a', 's', 'b', 'p', 'j'])->comment('属性：头条 推荐 幻灯 特荐 滚动 加粗 图片 跳转');
            $table->char('keywords', 30)->comment('关键词');
            $table->char('thumbnail', 100)->comment('封面图片');
            $table->char('source_name', 30)->comment('来源');
            $table->char('author_name', 20)->comment('作者');
            $table->char('description')->comment('描述');
            $table->string('filename', 40)->comment('html文件名');
            $table->unsignedBigInteger('view_num')->default(0)->comment('点击量');
            $table->boolean('is_make')->default(false)->comment('是已生成');
            $table->boolean('checked')->index()->comment('已审核');
            $table->timestamp('publish_at')->comment('发布时间');
            $table->timestamps();

            $table->index(['checked', 'channel_id', 'category_id', 'flag']);
            $table->index('publish_at');

            $table->comment('文档主表');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archive_index');
        Schema::dropIfExists('archives');
    }
};
