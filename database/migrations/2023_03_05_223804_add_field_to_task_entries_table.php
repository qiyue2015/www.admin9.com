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
        Schema::table('task_entries', function (Blueprint $table) {
            $table->boolean('status')->default(1)->index()->after('tags')->comment('状态 0任务关闭 1任务开启');
            $table->dropColumn('contents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_entries', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->longText('contents')->nullable();
        });
    }
};
