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
            // Check if columns don't exist before adding them to prevent duplicate crashes
            if (!Schema::hasColumn('course_quizzes', 'question_type')) {
                $table->string('question_type')->default('multiple_choice')->after('course_id');
            }
            if (!Schema::hasColumn('course_quizzes', 'option_c')) {
                $table->string('option_c')->nullable()->after('option_b');
            }
            if (!Schema::hasColumn('course_quizzes', 'option_d')) {
                $table->string('option_d')->nullable()->after('option_c');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_quizzes', function (Blueprint $table) {
            $table->dropColumn(['question_type', 'option_c', 'option_d']);
        });
    }
};