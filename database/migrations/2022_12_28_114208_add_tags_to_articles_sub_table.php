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
        collect(range(0, 9))->each(function ($i) {
            Schema::table('articles_'.$i, function (Blueprint $table) {
                $table->string('tags')->after('id')->comment('标签信息');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        collect(range(0, 9))->each(function ($i) {
            Schema::table('articles_'.$i, function (Blueprint $table) {
                $table->dropColumn('tags');
            });
        });
    }
};
