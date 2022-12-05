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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('category_id')->index()->comment('分类ID');
            $table->char('title', 100)->comment('标题');
            $table->string('digest', 255)->nullable()->comment('摘要');
            $table->unsignedBigInteger('view_num')->default(0)->comment('点击量');
            $table->string('cover_url', 255)->nullable()->comment('封面图片');
            $table->char('source_name', 20)->nullable()->comment('来源');
            $table->char('author_name', 20)->nullable()->comment('作者');
            $table->timestamps();
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
