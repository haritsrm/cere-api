<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('gender');
            $table->string('address');
            $table->string('phone');
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('parrent_name');
            $table->string('parrent_phone');
            $table->string('photo_url');
            $table->integer('balance');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('option1');
            $table->string('option2');
            $table->string('option3');
            $table->rememberToken();
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
        Schema::dropIfExists('students');
    }
}
