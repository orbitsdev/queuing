<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Branch::create([
            'name' => 'Main City Hall',
            'code' => 'HQ01',
            'address' => '123 Main Street, Metro City',
        ]);
    }
}
