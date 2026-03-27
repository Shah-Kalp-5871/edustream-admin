<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('order_items')->insert([
            [
                'id' => 1,
                'order_id' => 1,
                'item_type' => 'course',
                'item_id' => 1,
                'bundle_subjects' => null,
                'price' => '984.00',
                'created_at' => '2026-03-18 12:38:54',
                'updated_at' => '2026-03-18 12:38:54',
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'order_id' => 2,
                'item_type' => 'course',
                'item_id' => 1,
                'bundle_subjects' => null,
                'price' => '984.00',
                'created_at' => '2026-03-18 12:38:54',
                'updated_at' => '2026-03-18 12:38:54',
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'order_id' => 3,
                'item_type' => 'course',
                'item_id' => 1,
                'bundle_subjects' => null,
                'price' => '984.00',
                'created_at' => '2026-03-18 12:38:54',
                'updated_at' => '2026-03-18 12:38:54',
                'deleted_at' => null,
            ],
            [
                'id' => 4,
                'order_id' => 4,
                'item_type' => 'course',
                'item_id' => 1,
                'bundle_subjects' => null,
                'price' => '984.00',
                'created_at' => '2026-03-18 12:38:54',
                'updated_at' => '2026-03-18 12:38:54',
                'deleted_at' => null,
            ],
            [
                'id' => 5,
                'order_id' => 5,
                'item_type' => 'course',
                'item_id' => 1,
                'bundle_subjects' => null,
                'price' => '984.00',
                'created_at' => '2026-03-18 12:38:54',
                'updated_at' => '2026-03-18 12:38:54',
                'deleted_at' => null,
            ],
        ]);

    }
}
