<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_answers', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->integer('test_question_id');
            $table->boolean('is_right_answer');

            $table->foreign('test_question_id')->references('id')->on('test_questions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_answers');
    }
};
