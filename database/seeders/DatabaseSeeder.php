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
            BannersTableSeeder::class,
            NoteFoldersTableSeeder::class,
            NotesTableSeeder::class,
            VideoFoldersTableSeeder::class,
            VideosTableSeeder::class,
            QaPaperFoldersTableSeeder::class,
            QaPapersTableSeeder::class,
            QuizzesTableSeeder::class,
            QuizQuestionsTableSeeder::class,
            QuizOptionsTableSeeder::class,
            OrdersTableSeeder::class,
            OrderItemsTableSeeder::class,
            CartItemsTableSeeder::class,
            EnrollmentsTableSeeder::class,
            QuizAttemptsTableSeeder::class,
        ]);

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }
}
