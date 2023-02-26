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
            Schema::table('archives_extend_'.$i, function (Blueprint $table) {
                $table->json('display')->nullable()->after('content')->comment('头条问答');
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
            Schema::table('archives_extend_'.$i, function (Blueprint $table) {
                $table->dropColumn('display');
            });
        });
    }
};
