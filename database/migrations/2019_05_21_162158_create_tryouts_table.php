<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTryoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tryouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('lesson_id');
            $table->integer('class_id');
            $table->string('name');
            $table->string('instruction');
            $table->integer('duration');
            $table->integer('attempt_count');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('price');
            $table->string('scoring_system');
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
        Schema::dropIfExists('tryouts');
    }
}
