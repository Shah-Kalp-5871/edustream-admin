<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Support\Str;

class EducationContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Categories
        $categories = [
            ['name' => 'Primary', 'icon' => 'fa-solid fa-child'],
            ['name' => 'Secondary', 'icon' => 'fa-solid fa-user-graduate'],
        ];

        foreach ($categories as $index => $catData) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($catData['name'])],
                [
                    'name' => $catData['name'],
                    'icon_url' => $catData['icon'],
                    'status' => 'active',
                    'sort_order' => $index + 1
                ]
            );

            // 2. Add Courses (Standards)
            if ($catData['name'] === 'Primary') {
                $stds = [4, 5, 6, 7, 8];
            } else {
                $stds = [9, 10];
            }

            foreach ($stds as $sIndex => $stdNum) {
                // Formatting standard as requested: "ધોરણ 4"
                $courseName = "ધોરણ $stdNum";
                
                $course = Course::updateOrCreate(
                    ['slug' => "std-$stdNum"], // Unique slug for Each Std
                    [
                        'category_id' => $category->id,
                        'name' => $courseName,
                        'description' => "$courseName complete curriculum",
                        'price' => 999,
                        'status' => 'active',
                        'icon_url' => 'fa-solid fa-graduation-cap',
                        'color_code' => '#1565C0',
                        'sort_order' => $sIndex + 1
                    ]
                );

                // 3. Add Subjects (English, Social Science, Science, Maths)
                $subjects = [
                    ['name' => 'ગણિત', 'slug' => 'maths', 'icon' => 'fa-solid fa-calculator'],
                    ['name' => 'વિજ્ઞાન', 'slug' => 'science', 'icon' => 'fa-solid fa-microscope'],
                    ['name' => 'સામાજિક વિજ્ઞાન', 'slug' => 'social-science', 'icon' => 'fa-solid fa-earth-americas'],
                    ['name' => 'English', 'slug' => 'english', 'icon' => 'fa-solid fa-language'],
                ];

                foreach ($subjects as $subIndex => $subData) {
                    Subject::updateOrCreate(
                        ['slug' => "std-$stdNum-" . $subData['slug']],
                        [
                            'course_id' => $course->id,
                            'name' => $subData['name'],
                            'description' => $subData['name'] . " curriculum for " . $courseName,
                            'price' => 499,
                            'status' => 'active',
                            'icon_url' => $subData['icon'],
                            'color_code' => '#1565C0',
                            'sort_order' => $subIndex + 1
                        ]
                    );
                }
            }
        }
    }
}
