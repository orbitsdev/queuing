<?php

namespace Database\Seeders;

use App\Models\Queue;
use App\Models\Branch;
use App\Models\Counter;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class TestQueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates test queue data for today
     *
     * Usage: php artisan db:seed --class=TestQueueSeeder
     */
    public function run(): void
    {
        $branch = Branch::first();

        if (!$branch) {
            $this->command->error('No branch found. Please create a branch first.');
            return;
        }

        $services = Service::where('branch_id', $branch->id)->get();

        if ($services->isEmpty()) {
            $this->command->error('No services found for branch: ' . $branch->name);
            return;
        }

        // Get settings for ticket prefix
        $setting = Setting::where('branch_id', $branch->id)->first();
        $prefix = $setting?->ticket_prefix ?? 'QUE';
        $base = $setting?->queue_number_base ?? 1;

        // Count existing queues for today to continue numbering
        $existingCount = Queue::where('branch_id', $branch->id)
            ->whereDate('created_at', today())
            ->count();

        $this->command->info("Creating test queues for branch: {$branch->name}");
        $this->command->info("Existing queues today: {$existingCount}");

        // Create 10 waiting queues
        for ($i = 1; $i <= 10; $i++) {
            $number = $base + $existingCount + $i - 1;
            $service = $services->random();

            Queue::create([
                'branch_id' => $branch->id,
                'service_id' => $service->id,
                'counter_id' => null,
                'user_id' => null,
                'number' => $number,
                'ticket_number' => $prefix . $number,
                'status' => 'waiting',
            ]);

            $this->command->info("Created queue: {$prefix}{$number} - Service: {$service->name}");
        }

        $this->command->info('');
        $this->command->info("Successfully created 10 test queues!");
        $this->command->info("Total queues for today: " . ($existingCount + 10));
    }
}
