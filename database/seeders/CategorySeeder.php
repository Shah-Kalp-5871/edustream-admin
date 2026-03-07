<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Board Exams', 'slug' => 'board-exams', 'icon_url' => 'icons/boards.png'],
            ['name' => 'Engineering Entrance', 'slug' => 'engineering-entrance', 'icon_url' => 'icons/engineering.png'],
            ['name' => 'Medical Entrance', 'slug' => 'medical-entrance', 'icon_url' => 'icons/medical.png'],
            ['name' => 'Skill Development', 'slug' => 'skill-development', 'icon_url' => 'icons/skills.png'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
