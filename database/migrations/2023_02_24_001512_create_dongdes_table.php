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
        Schema::create('dongdes', function (Blueprint $table) {
            $table->comment('');
            $table->bigIncrements('id');
            $table->char('alias', 16)->index();
            $table->char('type', 10)->index();
            $table->boolean('status')->index();
            $table->unsignedSmallInteger('category_id')->index();
            $table->char('category_name', 120)->index();
            $table->unsignedSmallInteger('channel_id')->index();
            $table->char('title', 120);
            $table->char('subtitle', 100);
            $table->char('search_title', 100);
            $table->char('toutiao_title', 120);
            $table->char('sogou_title', 120);
            $table->text('keywords');
            $table->text('tags');
            $table->string('description');
            $table->string('cover');
            $table->timestamp('publish_at');
            $table->timestamps();
            $table->string('url');
            $table->unsignedInteger('user_id')->index();
            $table->unsignedBigInteger('dongde_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dongdes');
    }
};
