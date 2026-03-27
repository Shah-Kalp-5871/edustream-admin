<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MigrationsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('migrations')->insert([
            [
                'id' => 1,
                'queue' => '2026_03_06_115001_create_categories_table',
                'payload' => 1,
            ],
            [
                'id' => 2,
                'queue' => '2026_03_06_115002_create_courses_table',
                'payload' => 1,
            ],
            [
                'id' => 3,
                'queue' => '2026_03_06_115003_create_subjects_table',
                'payload' => 1,
            ],
            [
                'id' => 4,
                'queue' => '2026_03_06_115004_create_note_folders_table',
                'payload' => 1,
            ],
            [
                'id' => 5,
                'queue' => '2026_03_06_115005_create_video_folders_table',
                'payload' => 1,
            ],
            [
                'id' => 6,
                'queue' => '2026_03_06_115006_create_qa_paper_folders_table',
                'payload' => 1,
            ],
            [
                'id' => 7,
                'queue' => '2026_03_06_115007_create_notes_table',
                'payload' => 1,
            ],
            [
                'id' => 8,
                'queue' => '2026_03_06_115008_create_videos_table',
                'payload' => 1,
            ],
            [
                'id' => 9,
                'queue' => '2026_03_06_115009_create_qa_papers_table',
                'payload' => 1,
            ],
            [
                'id' => 10,
                'queue' => '2026_03_06_115518_create_admins_table',
                'payload' => 1,
            ],
            [
                'id' => 11,
                'queue' => '2026_03_06_115518_create_students_table',
                'payload' => 1,
            ],
            [
                'id' => 12,
                'queue' => '2026_03_06_115519_create_otp_verifications_table',
                'payload' => 1,
            ],
            [
                'id' => 13,
                'queue' => '2026_03_06_115519_create_refresh_tokens_table',
                'payload' => 1,
            ],
            [
                'id' => 14,
                'queue' => '2026_03_06_120001_create_quizzes_table',
                'payload' => 1,
            ],
            [
                'id' => 15,
                'queue' => '2026_03_06_120002_create_quiz_questions_table',
                'payload' => 1,
            ],
            [
                'id' => 16,
                'queue' => '2026_03_06_120003_create_quiz_options_table',
                'payload' => 1,
            ],
            [
                'id' => 17,
                'queue' => '2026_03_06_120004_create_quiz_attempts_table',
                'payload' => 1,
            ],
            [
                'id' => 18,
                'queue' => '2026_03_06_120005_create_quiz_answers_table',
                'payload' => 1,
            ],
            [
                'id' => 19,
                'queue' => '2026_03_06_120101_create_orders_table',
                'payload' => 1,
            ],
            [
                'id' => 20,
                'queue' => '2026_03_06_120102_create_order_items_table',
                'payload' => 1,
            ],
            [
                'id' => 21,
                'queue' => '2026_03_06_120103_create_enrollments_table',
                'payload' => 1,
            ],
            [
                'id' => 22,
                'queue' => '2026_03_06_120104_create_cart_items_table',
                'payload' => 1,
            ],
            [
                'id' => 23,
                'queue' => '2026_03_06_120233_create_activity_logs_table',
                'payload' => 1,
            ],
            [
                'id' => 24,
                'queue' => '2026_03_06_120234_create_settings_table',
                'payload' => 1,
            ],
            [
                'id' => 25,
                'queue' => '2026_03_07_042753_create_cache_table',
                'payload' => 1,
            ],
            [
                'id' => 26,
                'queue' => '2026_03_07_042753_create_sessions_table',
                'payload' => 1,
            ],
            [
                'id' => 27,
                'queue' => '2026_03_07_042754_create_jobs_table',
                'payload' => 1,
            ],
            [
                'id' => 28,
                'queue' => '2026_03_07_044017_add_icon_and_color_to_courses_and_subjects_tables',
                'payload' => 1,
            ],
            [
                'id' => 29,
                'queue' => '2026_03_07_054910_add_file_path_to_videos_table',
                'payload' => 1,
            ],
            [
                'id' => 30,
                'queue' => '2026_03_07_115605_add_course_id_to_students_table',
                'payload' => 1,
            ],
            [
                'id' => 31,
                'queue' => '2026_03_07_120102_create_banners_table',
                'payload' => 1,
            ],
            [
                'id' => 32,
                'queue' => '2026_03_07_123018_add_gradient_fields_to_banners_table',
                'payload' => 1,
            ],
            [
                'id' => 33,
                'queue' => '2026_03_09_073803_update_students_and_courses_for_recommendations',
                'payload' => 1,
            ],
            [
                'id' => 34,
                'queue' => '2026_03_09_090210_simplify_courses_and_students_logic',
                'payload' => 1,
            ],
            [
                'id' => 35,
                'queue' => '2026_03_10_063322_add_hls_path_to_videos_table',
                'payload' => 1,
            ],
            [
                'id' => 36,
                'queue' => '2026_03_10_075829_create_failed_jobs_table',
                'payload' => 1,
            ],
            [
                'id' => 37,
                'queue' => '2026_03_11_100851_update_cart_and_orders_for_purchase_flow',
                'payload' => 1,
            ],
            [
                'id' => 38,
                'queue' => '2026_03_16_071721_update_students_table_for_email_auth',
                'payload' => 1,
            ],
            [
                'id' => 39,
                'queue' => '2026_03_21_085620_add_is_free_to_courses_and_subjects_table',
                'payload' => 2,
            ],
            [
                'id' => 40,
                'queue' => '2026_03_21_102831_add_soft_deletes_to_all_tables',
                'payload' => 3,
            ],
        ]);
    }
}
