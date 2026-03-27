<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'id' => 7,
                'name' => 'Primary',
                'slug' => 'primary',
                'icon_url' => 'fa-solid fa-child',
                'status' => 'active',
                'sort_order' => 1,
                'created_at' => '2026-03-21 10:21:53',
                'updated_at' => '2026-03-21 10:21:53',
                'deleted_at' => null,
            ],
            [
                'id' => 8,
                'name' => 'Secondary',
                'slug' => 'secondary',
                'icon_url' => 'fa-solid fa-user-graduate',
                'status' => 'active',
                'sort_order' => 2,
                'created_at' => '2026-03-21 10:21:53',
                'updated_at' => '2026-03-21 10:21:53',
                'deleted_at' => null,
            ],
            [
                'id' => 9,
                'name' => 'GYANSADHNA',
                'slug' => 'gyansadhna',
                'icon_url' => 'fa-solid fa-folder',
                'status' => 'active',
                'sort_order' => 3,
                'created_at' => '2026-03-21 17:00:39',
                'updated_at' => '2026-03-21 17:00:39',
                'deleted_at' => null,
            ],
            [
                'id' => 10,
                'name' => 'test',
                'slug' => 'test',
                'icon_url' => 'fa-solid fa-folder',
                'status' => 'active',
                'sort_order' => 4,
                'created_at' => '2026-03-27 12:32:01',
                'updated_at' => '2026-03-27 12:32:01',
                'deleted_at' => null,
            ],
        ]);

    }
}
