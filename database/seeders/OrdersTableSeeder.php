<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                'id' => 1,
                'student_id' => 1,
                'order_number' => 'ORD-FV3VLWL1N9',
                'total_amount' => '984.00',
                'payment_status' => 'completed',
                'payment_method' => 'upi',
                'payment_id' => 'pay_TAlS1nFHEd',
                'razorpay_order_id' => null,
                'razorpay_payment_id' => null,
                'razorpay_signature' => null,
                'created_at' => '2026-03-18 12:38:54',
                'updated_at' => '2026-03-18 12:38:54',
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'student_id' => 2,
                'order_number' => 'ORD-NI5TSDZ1OK',
                'total_amount' => '984.00',
                'payment_status' => 'completed',
                'payment_method' => 'upi',
                'payment_id' => 'pay_Q656oG9m69',
                'razorpay_order_id' => null,
                'razorpay_payment_id' => null,
                'razorpay_signature' => null,
                'created_at' => '2026-03-18 12:38:54',
                'updated_at' => '2026-03-18 12:38:54',
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'student_id' => 3,
                'order_number' => 'ORD-BK3PV8WRZM',
                'total_amount' => '984.00',
                'payment_status' => 'completed',
                'payment_method' => 'upi',
                'payment_id' => 'pay_kraKaAxEfD',
                'razorpay_order_id' => null,
                'razorpay_payment_id' => null,
                'razorpay_signature' => null,
                'created_at' => '2026-03-18 12:38:54',
                'updated_at' => '2026-03-18 12:38:54',
                'deleted_at' => null,
            ],
            [
                'id' => 4,
                'student_id' => 4,
                'order_number' => 'ORD-CYGIWYD4EQ',
                'total_amount' => '984.00',
                'payment_status' => 'completed',
                'payment_method' => 'upi',
                'payment_id' => 'pay_FGz5bnmhbp',
                'razorpay_order_id' => null,
                'razorpay_payment_id' => null,
                'razorpay_signature' => null,
                'created_at' => '2026-03-18 12:38:54',
                'updated_at' => '2026-03-18 12:38:54',
                'deleted_at' => null,
            ],
            [
                'id' => 5,
                'student_id' => 5,
                'order_number' => 'ORD-0PCQWBZCJJ',
                'total_amount' => '984.00',
                'payment_status' => 'completed',
                'payment_method' => 'upi',
                'payment_id' => 'pay_CRmgCBTgtn',
                'razorpay_order_id' => null,
                'razorpay_payment_id' => null,
                'razorpay_signature' => null,
                'created_at' => '2026-03-18 12:38:54',
                'updated_at' => '2026-03-18 12:38:54',
                'deleted_at' => null,
            ],
        ]);

    }
}
