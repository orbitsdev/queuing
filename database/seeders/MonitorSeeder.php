<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Monitor;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MonitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branch = Branch::first();
        $services = Service::where('branch_id', $branch->id)->get();

        // Create a monitor
        $monitor = Monitor::create([
            'branch_id' => $branch->id,
            'name' => 'Lobby TV 1',
            'location' => 'Main Entrance Lobby',
            'description' => 'Main display near entrance'
        ]);

        // Attach ALL services to this monitor for testing
        $monitor->services()->sync($services->pluck('id')->toArray());
    }
}
