<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up() {
    Schema::create('attempt_answers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('quiz_attempt_id')->constrained()->onDelete('cascade');
        $table->foreignId('quiz_question_id')->constrained();
        $table->foreignId('question_option_id')->constrained();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempt_answers');
    }
};
