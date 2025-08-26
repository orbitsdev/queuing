<?php

namespace App\Console\Commands;

use App\Models\Queue;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireOldQueues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-old-queues {--hours=24} {--branch=} {--quiet}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire waiting queues that are older than the specified time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $branchId = $this->option('branch');
        $quiet = $this->option('quiet');
        
        if (!$quiet) {
            $this->info("Expiring queues older than {$hours} hours...");
        }
        
        $olderThan = Carbon::now()->subHours($hours);
        
        $count = Queue::expireOldQueues($olderThan, $branchId ? (int)$branchId : null, true);
        
        if (!$quiet) {
            if ($count > 0) {
                $this->info("Successfully expired {$count} queues.");
            } else {
                $this->info("No queues needed to be expired.");
            }
        }
        
        return 0;
    }
}
