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
        Schema::create('categories', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('parent_id')->index()->default(0)->comment('父ID');
            $table->char('name', 10)->comment('名称')->index();
            $table->char('alias', 10)->comment('alias')->nullable('')->index();
            $table->char('slug', 20)->nullable()->index();
            $table->smallInteger('sort')->default(0)->comment('排序');
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
