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
        $tables = [
            'categories', 'courses', 'subjects',
            'note_folders', 'notes',
            'video_folders', 'videos',
            'qa_paper_folders', 'qa_papers',
            'quizzes', 'quiz_questions', 'quiz_options', 'quiz_answers', 'quiz_attempts',
            'banners',
            'students',
            'enrollments',
            'orders', 'order_items',
            'cart_items'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $tableBlueprint) {
                // Check if column already exists to prevent errors during re-runs
                if (!Schema::hasColumn($tableBlueprint->getTable(), 'deleted_at')) {
                    $tableBlueprint->softDeletes();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'categories', 'courses', 'subjects',
            'note_folders', 'notes',
            'video_folders', 'videos',
            'qa_paper_folders', 'qa_papers',
            'quizzes', 'quiz_questions', 'quiz_options', 'quiz_answers', 'quiz_attempts',
            'banners',
            'students',
            'enrollments',
            'orders', 'order_items',
            'cart_items'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $tableBlueprint) {
                if (Schema::hasColumn($tableBlueprint->getTable(), 'deleted_at')) {
                    $tableBlueprint->dropSoftDeletes();
                }
            });
        }
    }
};
