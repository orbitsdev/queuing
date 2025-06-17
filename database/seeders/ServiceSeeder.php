<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $branch = Branch::first();

        $services = [
            ['name' => 'Cashier Payment', 'code' => 'C', 'description' => 'Payments and billing services'],
            ['name' => 'Information Desk', 'code' => 'I', 'description' => 'Customer help and support'],
        ];

        foreach ($services as $svc) {
            $branch->services()->create(array_merge($svc, ['last_ticket_number' => 0]));
        }
    }
}
