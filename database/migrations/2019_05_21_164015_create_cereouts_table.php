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
            $table->bigInteger('my_time')->nullable();
            $table->integer('score')->nullable();
            $table->integer('total_answer')->nullable();
            $table->integer('correct_answered')->nullable();
            $table->integer('incorrect_answered')->nullable();
            $table->integer('left_answered')->nullable();
            $table->string('result_status')->nullable();
            $table->timestamps();
        });

        Schema::table('cereouts', function(Blueprint $column) {
            $column->foreign('tryout_id')->references('id')->on('tryouts')->onDelete('cascade')->onUpdate('cascade');
            $column->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
