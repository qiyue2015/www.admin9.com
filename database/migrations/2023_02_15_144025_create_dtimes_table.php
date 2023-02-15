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
        Schema::create('dtimes', function (Blueprint $table) {
            $table->id();
            $table->char('title', 120);
            $table->string('url');
            $table->char('url_hash', 32)->unique();
            $table->boolean('status')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dtimes');
    }
};
