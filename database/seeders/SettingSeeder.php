<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'ticket_prefix_style', 'value' => 'branch_code'],
            ['key' => 'print_logo', 'value' => 'true'],
            ['key' => 'announcement_voice', 'value' => 'en_female'],
            ['key' => 'max_hold_time_minutes', 'value' => '5'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
        }
    }
}
