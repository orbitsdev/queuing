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
        // Create global default settings (branch_id = NULL)
        Setting::updateOrCreate(
            ['branch_id' => null],
            [
                'ticket_prefix' => 'QUE',
                'print_logo' => true,
                'queue_reset_daily' => true,
                'queue_reset_time' => '04:00',
                'default_break_message' => 'Not available',
                'queue_number_base' => 1,
            ]
        );

        // Create settings for each branch if they don't exist
        $branches = Branch::all();
        foreach ($branches as $branch) {
            Setting::firstOrCreate(
                ['branch_id' => $branch->id],
                [
                    'ticket_prefix' => 'QUE',
                    'print_logo' => true,
                    'queue_reset_daily' => true,
                    'queue_reset_time' => '04:00',
                    'default_break_message' => 'Not available',
                    'queue_number_base' => 1,
                ]
            );
        }
    }
}
