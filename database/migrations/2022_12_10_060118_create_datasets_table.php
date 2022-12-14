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
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->set('type', ['train', 'valid', 'test'])->index()->comment('类别');
            $table->char('category1', 20)->index()->comment('分类1');
            $table->char('category2', 20)->index()->comment('分类2');
            $table->longText('tags')->comment('分类');
            $table->char('title', 100)->comment('标题');
            $table->text('desc')->comment('短文本');
            $table->longText('body')->comment('长文本');
            $table->string('link')->comment('链接');
            $table->unsignedTinyInteger('status')->comment('状态');

            $table->index(['category1', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datasets');
    }
};
