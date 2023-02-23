<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->comment('');
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('category_id')->index()->comment('分类ID');
            $table->string('tags')->comment('标签信息');
            $table->char('title', 100)->fulltext()->comment('标题');
            $table->char('keywords', 50)->comment('关键词');
            $table->string('description')->comment('描述');
            $table->unsignedBigInteger('view_num')->default(0)->comment('点击量');
            $table->boolean('checked')->index()->comment('已审核');
            $table->boolean('status')->index();
            $table->string('cover_url')->comment('封面图片');
            $table->char('source_name', 30)->comment('来源');
            $table->char('author_name', 20)->comment('作者');
            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('updated_at')->nullable();

            $table->index(['category_id', 'checked']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
