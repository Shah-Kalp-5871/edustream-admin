<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $boardId = \App\Models\Category::where('slug', 'board-exams')->first()->id;
        $engId = \App\Models\Category::where('slug', 'engineering-entrance')->first()->id;

        $courses = [
            [
                'category_id' => $boardId,
                'name' => 'Class 10th Standard',
                'slug' => 'class-10th-standard',
                'description' => 'Complete preparation for 10th Board Exams.',
                'price' => 2000.00,
                'status' => 'active'
            ],
            [
                'category_id' => $boardId,
                'name' => 'Class 12th Standard',
                'slug' => 'class-12th-standard',
                'description' => 'Complete preparation for 12th Board Exams.',
                'price' => 2500.00,
                'status' => 'active'
            ],
            [
                'category_id' => $engId,
                'name' => 'JEE Main Foundation',
                'slug' => 'jee-main-foundation',
                'description' => 'Comprehensive JEE Main Foundation course.',
                'price' => 5000.00,
                'status' => 'active'
            ],
        ];

        foreach ($courses as $course) {
            \App\Models\Course::create($course);
        }
    }
}
