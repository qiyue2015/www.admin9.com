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
            $table->dropColumn('channel_id');
            $table->char('author_name', 20)->after('source_name')->change();
            $table->string('description')->after('title')->comment('描述');
            $table->string('keywords')->after('title')->comment('关键词');
        });

        Schema::dropIfExists('trains');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('tags_data');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedSmallInteger('channel_id')->index()->after('id')->comment('频道ID');
            $table->dropColumn('keywords');
            $table->dropColumn('description');
        });

        Schema::create('trains', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        Schema::create('tags_data', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};
