<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->integer('class_id');
            $table->integer('student_id');
            $table->dateTime('join_time');
            $table->dateTime('leave_time');

            $table->foreign('class_id')->references('id')->on('classes');
            $table->foreign('student_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
