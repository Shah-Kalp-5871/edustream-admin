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
        // Update cart_items table
        Schema::table('cart_items', function (Blueprint $table) {
            $table->string('item_type')->change();
            $table->json('bundle_subjects')->nullable()->after('item_id');
        });

        // Update order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('item_type')->change();
            $table->json('bundle_subjects')->nullable()->after('item_id');
        });

        // Update orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->string('razorpay_order_id')->nullable()->after('payment_id');
            $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
            $table->string('razorpay_signature')->nullable()->after('razorpay_payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn('bundle_subjects');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('bundle_subjects');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature']);
        });
    }
};
