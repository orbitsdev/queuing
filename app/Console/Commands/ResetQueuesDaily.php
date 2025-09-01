<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Branch;

class ResetQueuesDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-queues-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset ticket numbers for all branches based on their daily reset time and settings.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        Branch::with('setting', 'services')->each(function ($branch) use ($now) {
            $setting = $branch->setting;

            if (
                $setting &&
                $setting->queue_reset_daily &&
                $setting->queue_reset_time
            ) {
                $resetTime = Carbon::parse($setting->queue_reset_time)->setDateFrom($now);

                if (
                    $now->greaterThanOrEqualTo($resetTime) &&
                    (!$setting->last_reset_at || !$setting->last_reset_at->isToday())
                ) {
                    foreach ($branch->services as $service) {
                        // 1. Reset queue counters
                        $service->last_ticket_number = $setting->queue_number_base ?? 1;
                        $service->save();
                        
                        // 2. Delete ALL of today's queue records regardless of status
                        $service->queues()
                            ->whereDate('created_at', today())
                            ->delete();
                    }

                    $setting->last_reset_at = $now;
                    $setting->save();

                    $this->info("✅ Queue reset for branch: {$branch->name} at {$now->toTimeString()}");
                } else {
                    $this->info("⏭️ Skipped: {$branch->name} — already reset today or not time yet.");
                }
            }
        });

        $this->info('✔️ Queue reset check completed.');
    }
}
