<?php

namespace Database\Seeders;

use App\Models\Branch;
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
        // Global default settings (branch_id = NULL)
        $settings = [
            // Ticket Settings
            ['key' => 'ticket_prefix', 'value' => 'QUE'], // Simple prefix without placeholders
            ['key' => 'print_logo', 'value' => 'true'],

            // Queue Settings
            ['key' => 'queue_reset_daily', 'value' => 'true'],
            ['key' => 'queue_reset_time', 'value' => '00:00'],
            ['key' => 'default_break_message', 'value' => 'On break, please proceed to another counter.'],
        ];

        // Create or update global defaults
        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['branch_id' => null, 'key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }

        // Copy defaults to all existing branches if they don't have settings
        $branches = Branch::all();
        foreach ($branches as $branch) {
            foreach ($settings as $setting) {
                Setting::firstOrCreate(
                    ['branch_id' => $branch->id, 'key' => $setting['key']],
                    ['value' => $setting['value']]
                );
            }
        }
    }
}
