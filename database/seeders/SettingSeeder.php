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
            // Ticket Settings
            ['key' => 'ticket_prefix_style', 'value' => '{branch}-{number}'],
            ['key' => 'print_logo', 'value' => 'true'],

            // Queue Settings
            ['key' => 'queue_reset_daily', 'value' => 'true'],
            ['key' => 'queue_reset_time', 'value' => '00:00'],
            ['key' => 'default_break_message', 'value' => 'On break, please proceed to another counter.'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
        }
    }
}
