<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    protected array $tables = ['task_entries', 'task_docs'];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        collect($this->tables)->each(function ($table) {
            Schema::create($table, function (Blueprint $table) {
                $table->id();
                $table->char('hash', 32)->unique()->comment('唯一HASH');
                $table->char('title', 100);
                $table->char('tags', 30);
                $table->longText('contents')->nullable();
                $table->unsignedTinyInteger('run_num')->default(0)->comment('运行次数');
                $table->unsignedInteger('run_time')->index()->default(0)->comment('运行时间');
                $table->timestamps();
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
        collect($this->tables)->each(function ($table) {
            Schema::dropIfExists($table);
        });
    }
};
