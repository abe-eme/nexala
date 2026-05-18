<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            // Connects directly to the courses table you already built
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            
            $table->string('title');
            $table->enum('type', ['quiz', 'assignment']); // Differentiates automated vs manual testing
            $table->integer('passing_score')->default(80); // Minimum percentage required to pass
            
            // 🤖 CRITICAL FOR AI GENERATION
            // This column stores the complete AI-generated quiz questions array or assignment criteria as raw JSON.
            $table->json('questions_payload')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};