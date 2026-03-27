<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->insert([
            [
                'id' => 1,
                'name' => 'Super Admin',
                'email' => 'admin@edustream.com',
                'password' => '$2y$12$etN0nxrkZ9lcanbMWeXCveV79/HOerKljcmaPzV/.0ZGLcHeYb4aG',
                'avatar_url' => null,
                'status' => 'active',
                'remember_token' => null,
                'created_at' => '2026-03-18 12:38:53',
                'updated_at' => '2026-03-18 12:38:53',
            ],
        ]);

    }
}
