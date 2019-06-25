<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCerelisasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cerelisasi_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->bigInteger('user_id');
            $table->bigInteger('department_id');
            $table->bigInteger('total_point');
            $table->string('status')->nullable();
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
        Schema::dropIfExists('cerelisasis');
    }
}
