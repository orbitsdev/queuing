<?php

namespace Database\Seeders;

use App\Models\Branch;
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

        $counters = [
            ['name' => 'Counter 1', 'is_priority' => false, 'active' => true],
            ['name' => 'Counter 2', 'is_priority' => false, 'active' => true],
        ];

        foreach ($counters as $ctr) {
            $branch->counters()->create($ctr);
        }
    }
}
