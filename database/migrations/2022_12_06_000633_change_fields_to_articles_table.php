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
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('digest');
            $table->dropColumn('keyboard');
            $table->char('keywords', 30)->change();
            $table->string('source_name')->nullable(false)->change();
            $table->string('author_name')->nullable(false)->change();
            $table->string('cover_url')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('digest', 255)->nullable()->comment('摘要');
            $table->string('keyboard')->nullable()->comment('关键词');
            $table->string('cover_url')->nullable()->change();
            $table->string('source_name')->nullable()->change();
            $table->string('author_name')->nullable()->change();
        });
    }
};
