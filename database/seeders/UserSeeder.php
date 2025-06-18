<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branch = Branch::first();

        // Create superadmin (not tied to any branch)
        User::create([
            'branch_id' => null,
            'name' => 'Super Admin',
            'email' => 'superadmin@kiosqueeing.local',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        // Create branch admin
        User::create([
            'branch_id' => $branch->id,
            'name' => 'Admin User',
            'email' => 'admin@kiosqueeing.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create branch staff
        User::create([
            'branch_id' => $branch->id,
            'name' => 'Staff User',
            'email' => 'staff@kiosqueeing.local',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);
    }
}
