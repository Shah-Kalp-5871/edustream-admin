<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizzesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('quizzes')->insert([
            [
                'id' => 68,
                'subject_id' => 68,
                'title' => 'જ્ઞાનસાધના વિજ્ઞાન ભાગ 1',
                'description' => null,
                'total_marks' => '0',
                'passing_percentage' => 40,
                'time_limit_minutes' => 30,
                'status' => 'active',
                'sort_order' => 1,
                'created_at' => '2026-03-21 17:04:34',
                'updated_at' => '2026-03-26 17:27:51',
                'deleted_at' => null,
            ],
            [
                'id' => 69,
                'subject_id' => 68,
                'title' => 'જ્ઞાનસાધના વિજ્ઞાન ભાગ 2',
                'description' => null,
                'total_marks' => '0',
                'passing_percentage' => 40,
                'time_limit_minutes' => 30,
                'status' => 'active',
                'sort_order' => 2,
                'created_at' => '2026-03-25 07:44:50',
                'updated_at' => '2026-03-26 14:00:26',
                'deleted_at' => '2026-03-26 14:00:26',
            ],
            [
                'id' => 70,
                'subject_id' => 37,
                'title' => 'test',
                'description' => 'test',
                'total_marks' => '0',
                'passing_percentage' => 40,
                'time_limit_minutes' => 30,
                'status' => 'inactive',
                'sort_order' => 1,
                'created_at' => '2026-03-25 07:49:14',
                'updated_at' => '2026-03-25 07:52:37',
                'deleted_at' => '2026-03-25 07:52:37',
            ],
            [
                'id' => 71,
                'subject_id' => 68,
                'title' => 'જ્ઞાનસાધના વિજ્ઞાન ભાગ 3',
                'description' => null,
                'total_marks' => '0',
                'passing_percentage' => 40,
                'time_limit_minutes' => 30,
                'status' => 'active',
                'sort_order' => 3,
                'created_at' => '2026-03-26 13:43:15',
                'updated_at' => '2026-03-26 17:28:20',
                'deleted_at' => null,
            ],
            [
                'id' => 72,
                'subject_id' => 68,
                'title' => 'જ્ઞાનસાધના  વિજ્ઞાન ભાગ 2',
                'description' => null,
                'total_marks' => '0',
                'passing_percentage' => 40,
                'time_limit_minutes' => 30,
                'status' => 'active',
                'sort_order' => 4,
                'created_at' => '2026-03-26 14:02:31',
                'updated_at' => '2026-03-26 17:29:38',
                'deleted_at' => null,
            ],
            [
                'id' => 73,
                'subject_id' => 68,
                'title' => 'જ્ઞાનસાધના વિજ્ઞાન ભાગ 4',
                'description' => null,
                'total_marks' => '0',
                'passing_percentage' => 40,
                'time_limit_minutes' => 30,
                'status' => 'active',
                'sort_order' => 5,
                'created_at' => '2026-03-26 17:30:13',
                'updated_at' => '2026-03-26 18:37:54',
                'deleted_at' => null,
            ],
        ]);

    }
}
