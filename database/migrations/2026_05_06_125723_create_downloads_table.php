<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('downloads', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $blueprint->string('content_id')->nullable();
            $blueprint->string('content_type')->nullable(); // video, pdf, note, paper
            $blueprint->string('title');
            $blueprint->string('file_name');
            $blueprint->string('file_path')->nullable(); // local path on device
            $blueprint->string('file_url')->nullable(); // server url
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('downloads');
    }
};
