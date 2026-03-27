<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizAttemptsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('quiz_attempts')->insert([
            [
                'id' => 40,
                'quiz_id' => 68,
                'student_id' => 11,
                'score' => 12,
                'percentage' => '40.00',
                'status' => 'passed',
                'completed_at' => '2026-03-25 07:48:37',
                'created_at' => '2026-03-25 07:48:37',
                'updated_at' => '2026-03-25 07:48:37',
                'deleted_at' => null,
            ],
            [
                'id' => 41,
                'quiz_id' => 69,
                'student_id' => 12,
                'score' => 5,
                'percentage' => '17.24',
                'status' => 'failed',
                'completed_at' => '2026-03-25 11:28:32',
                'created_at' => '2026-03-25 11:28:32',
                'updated_at' => '2026-03-25 11:28:32',
                'deleted_at' => null,
            ],
            [
                'id' => 42,
                'quiz_id' => 68,
                'student_id' => 12,
                'score' => 11,
                'percentage' => '36.67',
                'status' => 'failed',
                'completed_at' => '2026-03-25 11:29:11',
                'created_at' => '2026-03-25 11:29:11',
                'updated_at' => '2026-03-25 11:29:11',
                'deleted_at' => null,
            ],
            [
                'id' => 43,
                'quiz_id' => 68,
                'student_id' => 12,
                'score' => 11,
                'percentage' => '36.67',
                'status' => 'failed',
                'completed_at' => '2026-03-26 11:16:35',
                'created_at' => '2026-03-26 11:16:35',
                'updated_at' => '2026-03-26 11:16:35',
                'deleted_at' => null,
            ],
            [
                'id' => 44,
                'quiz_id' => 68,
                'student_id' => 12,
                'score' => 9,
                'percentage' => '30.00',
                'status' => 'failed',
                'completed_at' => '2026-03-26 11:17:05',
                'created_at' => '2026-03-26 11:17:05',
                'updated_at' => '2026-03-26 11:17:05',
                'deleted_at' => null,
            ],
        ]);

    }
}
