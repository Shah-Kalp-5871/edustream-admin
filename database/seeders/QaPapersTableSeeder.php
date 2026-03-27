<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QaPapersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('qa_papers')->insert([
            [
                'id' => 61,
                'subject_id' => 68,
                'folder_id' => null,
                'name' => 'cgms-2023 gujarati medium1_102024.pdf',
                'description' => null,
                'file_path' => 'qa_papers/LAQVMcxP6SqVO2jm92MlaZU37J6ySgNxf5FCfEab.pdf',
                'file_type' => 'pdf',
                'is_free' => '0',
                'status' => 'active',
                'sort_order' => 1,
                'created_at' => '2026-03-23 15:59:19',
                'updated_at' => '2026-03-23 16:00:52',
                'deleted_at' => '2026-03-23 16:00:52',
            ],
            [
                'id' => 62,
                'subject_id' => 68,
                'folder_id' => null,
                'name' => 'જ્ઞાનસાધના 2024 પ્રશ્નપત્ર.pdf',
                'description' => null,
                'file_path' => 'qa_papers/1vSysTcKmYUAQ6YAOgVJtvE5l7JxX2yogAJqUY6h.pdf',
                'file_type' => 'pdf',
                'is_free' => '0',
                'status' => 'active',
                'sort_order' => 2,
                'created_at' => '2026-03-23 16:00:44',
                'updated_at' => '2026-03-23 16:00:44',
                'deleted_at' => null,
            ],
            [
                'id' => 63,
                'subject_id' => 68,
                'folder_id' => null,
                'name' => 'જ્ઞાનસાધના 2023 પ્રશ્નપત્ર.pdf',
                'description' => null,
                'file_path' => 'qa_papers/DGEM9VuLflf1ZR2q2SdnnIe1aik0ZEmQcou827Ei.pdf',
                'file_type' => 'pdf',
                'is_free' => '0',
                'status' => 'active',
                'sort_order' => 3,
                'created_at' => '2026-03-23 16:01:53',
                'updated_at' => '2026-03-23 16:01:53',
                'deleted_at' => null,
            ],
            [
                'id' => 64,
                'subject_id' => 68,
                'folder_id' => null,
                'name' => 'જ્ઞાનસાધના 2025 પ્રશ્નપત્ર',
                'description' => null,
                'file_path' => 'qa_papers/04fB8qLm6Edraiprh36GthRxHtekiuoOe1XNZJh7.pdf',
                'file_type' => 'pdf',
                'is_free' => '0',
                'status' => 'active',
                'sort_order' => 4,
                'created_at' => '2026-03-23 16:02:29',
                'updated_at' => '2026-03-23 16:03:13',
                'deleted_at' => null,
            ],
        ]);

    }
}
