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
        Schema::create('tags_data', function (Blueprint $table) {
            $table->id();
            $table->string('tags_id')->comment('Tags ID');
            $table->unsignedSmallInteger('channel_id')->comment('频道ID');
            $table->unsignedBigInteger('article_id')->comment('信息ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags_data');
    }
};
