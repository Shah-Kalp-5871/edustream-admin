<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'EduStream', 'group' => 'general', 'type' => 'string'],
            ['key' => 'contact_email', 'value' => 'support@edustream.com', 'group' => 'general', 'type' => 'string'],
            ['key' => 'currency_symbol', 'value' => '₹', 'group' => 'commerce', 'type' => 'string'],
            ['key' => 'app_version', 'value' => '1.0.0', 'group' => 'system', 'type' => 'string'],
            ['key' => 'maintenance_mode', 'value' => '0', 'group' => 'system', 'type' => 'boolean'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::create($setting);
        }
    }
}
