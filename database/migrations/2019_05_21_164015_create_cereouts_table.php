<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCereoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cereouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tryout_id');
            $table->bigInteger('user_id');
            $table->bigInteger('my_time');
            $table->integer('score');
            $table->integer('total_answer');
            $table->integer('correct_answered');
            $table->integer('incorrect_answered');
            $table->integer('left_answered');
            $table->string('result_status');
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
        Schema::dropIfExists('cereouts');
    }
}
