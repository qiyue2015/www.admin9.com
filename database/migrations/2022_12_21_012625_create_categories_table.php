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
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_last')->default(0)->comment('0非终极栏目 1终极栏目');
            $table->unsignedTinyInteger('is_list')->default(0)->comment('0封面模式 1列表模式');
            $table->text('children')->comment('子栏目ID集合');
            $table->text('parents')->comment('父栏目ID集合');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('ls_last');
            $table->dropColumn('is_list');
            $table->dropColumn('children');
            $table->dropColumn('parents');
        });
    }
};