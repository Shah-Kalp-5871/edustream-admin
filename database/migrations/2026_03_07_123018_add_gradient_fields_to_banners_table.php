<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('icon')->default('fa-graduation-cap')->after('title');
            $table->string('color_start')->default('#1565C0')->after('icon');
            $table->string('color_end')->default('#7B1FA2')->after('color_start');
            $table->string('subtitle')->nullable()->after('color_end');
            $table->string('image_path')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['icon', 'color_start', 'color_end', 'subtitle']);
        });
    }
};
