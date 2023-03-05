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
        Schema::create('archives', function (Blueprint $table) {
            $table->comment('');
            $table->bigIncrements('id');
            $table->string('title');
            $table->unsignedSmallInteger('category_id')->default(0)->comment('分类ID');
            $table->set('flag', ['c', 'h', 'p', 'f', 's', 'j', 'a', 'b'])->nullable();
            $table->char('subtitle', 100)->nullable();
            $table->char('search_title', 100)->nullable();
            $table->char('keywords', 50)->nullable();
            $table->string('description')->nullable();
            $table->string('cover')->nullable();
            $table->string('tags')->nullable();
            $table->boolean('has_cover')->default(false)->index()->comment('是否有封面');
            $table->text('images')->nullable();
            $table->unsignedBigInteger('view_num')->default(0)->comment('点击量');
            $table->boolean('checked')->default(false)->index();
            $table->boolean('published')->default(false);
            $table->timestamp('publish_at')->nullable();
            $table->boolean('is_html')->default(false)->index()->comment('生成 PC html');
            $table->boolean('is_wap_html')->default(false)->index()->comment('生成 WAP html');
            $table->boolean('is_sitemap')->default(false)->index()->comment('生成');
            $table->char('task_id', 32)->nullable()->index();
            $table->timestamps();

            $table->index(['category_id', 'has_cover', 'published', 'flag', 'publish_at'], 'main_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archives');
    }
};
