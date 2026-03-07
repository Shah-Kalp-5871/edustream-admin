<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@edustream.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'status' => 'active',
        ]);
    }
}
