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
            if (Schema::hasColumn('students', 'selected_std')) {
                $table->dropColumn('selected_std');
            }
        });

        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'std')) {
                $table->dropColumn('std');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->integer('selected_std')->nullable()->after('mobile');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->integer('std')->nullable()->after('name');
        });
    }
};
