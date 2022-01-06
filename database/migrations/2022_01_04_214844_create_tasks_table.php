<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('subject_id', false, true)->index();
            $table->text('description')->nullable();
            $table->integer('status_id', false, true)->index();
            $table->integer('ddate', false, true)->nullable()->index();
            $table->integer('adate', false, true)->index();
            $table->integer('edate', false, true)->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
