<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->enum('auth_mode', ['otp', 'password'])->default('otp')->after('email');
        });

        // Modify the enum on otp_verifications table to include switch_mode and reset
        // Using DB statement because Doctrine DBAL may have issues with changing ENUM columns directly in some Laravel versions
        DB::statement("ALTER TABLE otp_verifications MODIFY COLUMN purpose ENUM('login', 'signup', 'reset', 'switch_mode') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('auth_mode');
        });

        DB::statement("ALTER TABLE otp_verifications MODIFY COLUMN purpose ENUM('login', 'signup', 'reset') NOT NULL");
    }
};
