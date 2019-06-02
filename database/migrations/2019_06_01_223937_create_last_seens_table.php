<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLastSeensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('last_seens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('video_id')->nullable();
            $table->bigInteger('quiz_id')->nullable();
            $table->bigInteger('text_id')->nullable();
            $table->bigInteger('user_id');
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
        Schema::dropIfExists('last_seens');
    }
}
