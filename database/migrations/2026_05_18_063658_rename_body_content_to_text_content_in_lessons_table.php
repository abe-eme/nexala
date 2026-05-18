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
        Schema::table('lessons', function (Blueprint $table) {
            // If the old body_content column is still there, let's drop it 
            // since your table already has the working text_content column.
            if (Schema::hasColumn('lessons', 'body_content')) {
                $table->dropColumn('body_content');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            // Re-create it as a nullable column if you ever roll back
            if (!Schema::hasColumn('lessons', 'body_content')) {
                $table->longText('body_content')->nullable();
            }
        });
    }
};