<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartItemsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cart_items')->insert([
            [
                'id' => 1,
                'student_id' => 12,
                'item_type' => 'App\\\\Models\\\\Subject',
                'item_id' => 31,
                'bundle_subjects' => null,
                'price' => '0.00',
                'created_at' => '2026-03-20 16:30:00',
                'updated_at' => '2026-03-20 16:30:00',
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'student_id' => 12,
                'item_type' => 'App\\\\Models\\\\Course',
                'item_id' => 13,
                'bundle_subjects' => null,
                'price' => '0.00',
                'created_at' => '2026-03-20 17:22:44',
                'updated_at' => '2026-03-20 17:22:44',
                'deleted_at' => null,
            ],
        ]);

    }
}
