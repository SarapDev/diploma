<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('class_id');

            $table->foreign('class_id')->references('id')->on('classes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
