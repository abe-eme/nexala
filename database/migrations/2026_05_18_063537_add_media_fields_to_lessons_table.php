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
            // Adds structural media components directly after the lesson title field
            if (!Schema::hasColumn('lessons', 'media_type')) {
                $table->string('media_type')->default('text')->after('title');
            }
            if (!Schema::hasColumn('lessons', 'text_content')) {
                $table->longText('text_content')->nullable()->after('media_type');
            }
            if (!Schema::hasColumn('lessons', 'media_path')) {
                $table->string('media_path')->nullable()->after('text_content');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            // Drop layout flags for safe database rollbacks
            $table->dropColumn(['media_type', 'text_content', 'media_path']);
        });
    }
};