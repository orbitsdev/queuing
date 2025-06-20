<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branch = Branch::first();

        // Get all services
        $services = Service::all();
        
        $counters = [
            ['name' => 'Counter 1', 'is_priority' => false, 'active' => true],
            ['name' => 'Counter 2', 'is_priority' => false, 'active' => true],
        ];

        foreach ($counters as $ctr) {
            // Create counter
            $counter = $branch->counters()->create($ctr);
            
            // Attach all services to this counter
            $counter->services()->attach($services->pluck('id')->toArray());
        }
    }
}
