<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendance', function (Blueprint $blueprint) {
            $blueprint->foreign('student_id')->references('id')->on('students');
        });
    }

    public function down()
    {
        Schema::table('attendance', function (Blueprint $blueprint) {
            $blueprint->dropForeign('student_id');
        });
    }
};
