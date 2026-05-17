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
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        
        // OUR NEW SECURITY COLUMNS
        $table->string('role')->default('student'); // 'student' or 'teacher'
        $table->string('status')->default('approved'); // 'approved' or 'pending'
        $table->string('ip_address', 45)->nullable(); // Tracks network to prevent spam
        
        $table->rememberToken();
        $table->timestamps();
    });

    // Keep the default sessions and password_reset_tokens tables below...
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
