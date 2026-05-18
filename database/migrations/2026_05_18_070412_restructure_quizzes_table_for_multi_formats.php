<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('course_quizzes', function (Blueprint $table) {
            // Safely inject structural question formatting definitions if they are missing
            if (!Schema::hasColumn('course_quizzes', 'question_type')) {
                $table->string('question_type')->default('multiple_choice')->after('course_id');
            }
            
            // Alter columns to allow null value variants for short answers and workout styles
            $table->string('option_a')->nullable()->change();
            $table->string('option_b')->nullable()->change();
            $table->text('correct_option')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_quizzes', function (Blueprint $table) {
            if (Schema::hasColumn('course_quizzes', 'question_type')) {
                $table->dropColumn('question_type');
            }
        });
    }
};