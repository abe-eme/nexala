<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The Student
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            
            // Payload handling
            $table->json('student_answers_payload')->nullable(); // Stores selected multiple-choice indices
            $table->string('submission_url')->nullable(); // Stores GitHub / external link for practical assignments
            
            // Grading metrics
            $table->integer('numerical_grade')->nullable(); // Score out of 100
            $table->text('teacher_feedback')->nullable(); // Manual critique text from the teacher
            
            // Status state management
            $table->enum('status', ['pending', 'approved', 'requires_revision'])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_submissions');
    }
};