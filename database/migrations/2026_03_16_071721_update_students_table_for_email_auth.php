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
            $table->string('email')->unique()->change();
            $table->string('mobile', 15)->nullable()->change();
            if (!Schema::hasColumn('students', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('mobile_verified_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('mobile', 15)->unique()->change();
            $table->dropColumn('email_verified_at');
        });
    }
};
