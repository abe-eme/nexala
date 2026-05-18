<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_quizzes', function (Blueprint $table) {
            $table->string('option_c')->nullable()->after('option_b');
            $table->string('option_d')->nullable()->after('option_c');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->unsignedBigInteger('prerequisite_lesson_id')->nullable()->after('course_id');
            $table->foreign('prerequisite_lesson_id')->references('id')->on('lessons')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('course_quizzes', function (Blueprint $table) {
            $table->dropColumn(['option_c', 'option_d']);
        });
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['prerequisite_lesson_id']);
            $table->dropColumn('prerequisite_lesson_id');
        });
    }
};