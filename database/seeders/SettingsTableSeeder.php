<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->insert([
            [
                'id' => 1,
                'key' => 'site_name',
                'value' => 'EduStream',
                'group' => 'general',
                'type' => 'string',
                'created_at' => '2026-03-18 12:38:53',
                'updated_at' => '2026-03-18 12:38:53',
            ],
            [
                'id' => 2,
                'key' => 'contact_email',
                'value' => 'support@edustream.com',
                'group' => 'general',
                'type' => 'string',
                'created_at' => '2026-03-18 12:38:53',
                'updated_at' => '2026-03-18 12:38:53',
            ],
            [
                'id' => 3,
                'key' => 'currency_symbol',
                'value' => '₹',
                'group' => 'commerce',
                'type' => 'string',
                'created_at' => '2026-03-18 12:38:53',
                'updated_at' => '2026-03-18 12:38:53',
            ],
            [
                'id' => 4,
                'key' => 'app_version',
                'value' => '1.0.0',
                'group' => 'system',
                'type' => 'string',
                'created_at' => '2026-03-18 12:38:53',
                'updated_at' => '2026-03-18 12:38:53',
            ],
            [
                'id' => 5,
                'key' => 'maintenance_mode',
                'value' => '0',
                'group' => 'system',
                'type' => 'boolean',
                'created_at' => '2026-03-18 12:38:53',
                'updated_at' => '2026-03-18 12:38:53',
            ],
        ]);

    }
}
