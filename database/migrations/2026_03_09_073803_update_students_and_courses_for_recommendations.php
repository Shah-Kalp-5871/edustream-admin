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
        Schema::table('students', function (Blueprint $table) {
            $table->integer('selected_std')->nullable()->after('mobile');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->integer('std')->nullable()->after('name');
            $table->boolean('is_recommended')->default(false)->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('selected_std');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['std', 'is_recommended']);
        });
    }
};
