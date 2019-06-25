<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cereout_id');
            $table->bigInteger('question_id');
            $table->bigInteger('answer')->nullable();
            $table->timestamps();
        });

        Schema::table('answers', function(Blueprint $column) {
            $column->foreign('cereout_id')->references('id')->on('cereouts')->onDelete('cascade')->onUpdate('cascade');
            $column->foreign('question_id')->references('id')->on('questions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
