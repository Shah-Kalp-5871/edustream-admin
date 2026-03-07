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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('icon_url')->nullable()->after('description');
            $table->string('color_code')->nullable()->after('icon_url');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->string('icon_url')->nullable()->after('description');
            $table->string('color_code')->nullable()->after('icon_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['icon_url', 'color_code']);
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['icon_url', 'color_code']);
        });
    }
};
