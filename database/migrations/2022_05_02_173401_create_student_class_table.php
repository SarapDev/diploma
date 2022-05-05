<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_class', function (Blueprint $table) {
            $table->integer('student_id');
            $table->integer('class_id');

            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('class_id')->references('id')->on('classes');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_class');
    }
};
