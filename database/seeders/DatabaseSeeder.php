<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\BranchSeeder;
use Database\Seeders\CounterSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\QueueSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BranchSeeder::class,
            ServiceSeeder::class,
            CounterSeeder::class,
            UserSeeder::class,
            SettingSeeder::class,
            QueueSeeder::class,
            MonitorSeeder::class,
        ]);
    }
}
