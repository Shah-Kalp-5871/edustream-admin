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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('folder_id')->nullable()->constrained('video_folders')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('video_url');
            $table->string('video_source')->default('local'); // local, youtube, vimeo
            $table->string('duration')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->boolean('is_free')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
