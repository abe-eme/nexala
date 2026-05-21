<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            // Adds a text field for instructor notes if it doesn't exist
            if (!Schema::hasColumn('quiz_attempts', 'feedback')) {
                $table->text('feedback')->nullable()->after('score');
            }
            // Adds an evaluation status tracking layout if missing
            if (!Schema::hasColumn('quiz_attempts', 'status')) {
                $table->string('status')->default('completed')->after('feedback');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn(['feedback', 'status']);
        });
    }
};