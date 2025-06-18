<?php

namespace Database\Seeders;

use App\Models\Queue;
use App\Models\Branch;
use App\Models\Counter;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class QueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all branches
        $branches = Branch::all();
        
        foreach ($branches as $branch) {
            // Get services and counters for this branch
            $services = Service::all();
            $counters = Counter::where('branch_id', $branch->id)->get();
            $staffUsers = User::where('role', 'staff')->where('branch_id', $branch->id)->get();
            
            if ($counters->isEmpty() || $services->isEmpty() || $staffUsers->isEmpty()) {
                continue;
            }
            
            // Create 10 queues per branch with different statuses
            $statuses = ['waiting', 'called', 'serving', 'completed', 'skipped', 'held'];
            
            for ($i = 1; $i <= 20; $i++) {
                $service = $services->random();
                $counter = $counters->random();
                $user = $staffUsers->random();
                $status = $statuses[array_rand($statuses)];
                
                // Generate ticket number with branch prefix
                $ticketNumber = $branch->code . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                
                // Create queue entry
                Queue::create([
                    'branch_id' => $branch->id,
                    'service_id' => $service->id,
                    'counter_id' => $status === 'waiting' ? null : $counter->id,
                    'user_id' => $status === 'waiting' ? null : $user->id,
                    'ticket_number' => $ticketNumber,
                    'status' => $status,
                    'called_at' => in_array($status, ['called', 'serving', 'completed', 'skipped', 'held']) ? now()->subMinutes(rand(5, 60)) : null,
                    'serving_at' => in_array($status, ['serving', 'completed', 'skipped', 'held']) ? now()->subMinutes(rand(1, 30)) : null,
                    'served_at' => $status === 'completed' ? now()->subMinutes(rand(1, 15)) : null,
                    'skipped_at' => $status === 'skipped' ? now()->subMinutes(rand(1, 15)) : null,
                    'hold_started_at' => $status === 'held' ? now()->subMinutes(rand(1, 15)) : null,
                    'hold_reason' => $status === 'held' ? 'Missing documents' : null,
                ]);
            }
        }
    }
}
