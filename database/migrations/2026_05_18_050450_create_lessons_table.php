<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            // Link directly to the course migration you already created
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            
            $table->string('title');
            $table->text('body_content'); // For text, markdown, or AI-generated lecture material
            $table->string('media_url')->nullable(); // For WebRTC recorded videos/audio paths
            
            // 🔑 CRITICAL FOR SELF-PACED SEQUENCING
            $table->integer('unlock_order')->default(1); // e.g., Lesson 1, Lesson 2, Lesson 3
            $table->boolean('requires_previous_complete')->default(true); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};