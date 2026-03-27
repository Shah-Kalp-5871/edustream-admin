<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminsTableSeeder::class,
            CartItemsTableSeeder::class,
            CategoriesTableSeeder::class,
            CoursesTableSeeder::class,
            OrdersTableSeeder::class,
            OrderItemsTableSeeder::class,
            QaPapersTableSeeder::class,
            QuizzesTableSeeder::class,
            QuizAttemptsTableSeeder::class,
            QuizOptionsTableSeeder::class,
            QuizQuestionsTableSeeder::class,
            SettingsTableSeeder::class,
            StudentsTableSeeder::class,
            SubjectsTableSeeder::class
        ]);
    }
}
