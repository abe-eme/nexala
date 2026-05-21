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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys linking to your existing users and course_assignments tables
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            
            // Optional: If you want to link directly to a specific assignment row
            // $table->foreignId('course_assignment_id')->constrained()->onDelete('cascade');

            // Text content for student text answers, links, or descriptions
            $table->text('submission_payload')->nullable();
            
            // File path field for uploaded files/documents
            $table->string('file_path')->nullable();

            // Teacher evaluation fields
            $table->integer('score')->nullable();
            $table->text('feedback')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};