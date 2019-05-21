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
            $table->increments('id');
            $table->string('name', 50);
            $table->enum('gender', ['laki-laki', 'perempuan']);
            $table->text('address');
            $table->string('phone', 14);
            $table->string('birth_place', 50);
            $table->date('birth_date');
            $table->string('parrent_name', 50);
            $table->string('parrent_phone', 14);
            $table->text('photo_url');
            $table->integer('balance');
            $table->string('email');
            $table->string('password');
            $table->string('option1');
            $table->string('option2');
            $table->string('option3');
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
