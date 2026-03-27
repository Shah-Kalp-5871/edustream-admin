<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        $this->call([
            AdminsTableSeeder::class,
            StudentsTableSeeder::class,
            CategoriesTableSeeder::class,
            CoursesTableSeeder::class,
            SubjectsTableSeeder::class,
            SettingsTableSeeder::class,
            // Tables with actual data
            QaPapersTableSeeder::class,
            QuizzesTableSeeder::class,
            QuizQuestionsTableSeeder::class,
            QuizOptionsTableSeeder::class,
            OrdersTableSeeder::class,
            OrderItemsTableSeeder::class,
            CartItemsTableSeeder::class,
            QuizAttemptsTableSeeder::class,
        ]);

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }
}
