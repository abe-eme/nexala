<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::create('course_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('course_id')->constrained()->onDelete('cascade');
        $table->enum('status', ['enrolled', 'completed'])->default('enrolled');
        $table->enum('certificate_status', ['none', 'pending', 'issued'])->default('none');
        $table->decimal('final_grade', 5, 2)->nullable();
        $table->timestamps();
    });
}
    public function down(): void { Schema::dropIfExists('course_user'); }
};