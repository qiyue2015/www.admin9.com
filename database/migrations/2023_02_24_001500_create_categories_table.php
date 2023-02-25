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
        Schema::create('categories', function (Blueprint $table) {
            $table->comment('');
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('parent_id')->default(0)->index()->comment('父ID');
            $table->char('name', 10)->index()->comment('名称');
            $table->char('alias', 10)->nullable()->index()->comment('alias');
            $table->char('slug', 20)->nullable()->index();
            $table->smallInteger('sort')->default(0)->comment('排序');
            $table->unsignedInteger('num')->default(0)->comment('分类信息数量');
            $table->unsignedSmallInteger('is_show')->default(0)->index()->comment('导航展示 1展示');
            $table->unsignedTinyInteger('is_last')->default(0)->comment('0非终极栏目 1终极栏目');
            $table->unsignedTinyInteger('is_list')->default(0)->comment('0封面模式 1列表模式');
            $table->text('children')->nullable()->comment('子栏目ID集合');
            $table->text('parents')->nullable()->comment('父栏目ID集合');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
