<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_questions', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->integer('test_id');

            $table->foreign('test_id')->references('id')->on('tests');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_questions');
    }
};
