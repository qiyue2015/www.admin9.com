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
            Schema::create('archives_extend_'.$i, function (Blueprint $table) use ($i) {
                $table->comment('文档扩展表'.$i);
                $table->unsignedBigInteger('id')->primary();
                $table->mediumText('content')->nullable()->comment('内容');
                $table->foreign(['id'])->references(['id'])->on('archives')->onDelete('CASCADE');
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
            Schema::dropIfExists('archives_extend_'.$i);
        });
    }
};
