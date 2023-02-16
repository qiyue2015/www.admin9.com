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
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->char('subtitle', 100)->nullable();
            $table->char('search_title', 100)->nullable();
            $table->char('keywords', 30)->nullable();
            $table->string('description')->nullable();
            $table->string('cover')->nullable();
            $table->text('images')->nullable();
            $table->unsignedBigInteger('view_num')->default(0)->comment('点击量');
            $table->boolean('is_publish');
            $table->timestamp('publish_at');
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
        Schema::dropIfExists('archives');
    }
};
