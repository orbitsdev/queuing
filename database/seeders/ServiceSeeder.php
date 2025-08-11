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
            // Business
            ['name' => 'Business Online Billing and Payment', 'code' => 'BOBP', 'description' => 'Business'],
            ['name' => 'New Business Application', 'code' => 'NBA', 'description' => 'Business'],
            ['name' => 'Renew Business Application', 'code' => 'RBA', 'description' => 'Business'],

            // Real Property
            ['name' => 'Realty Tax Online Billing and Payment', 'code' => 'RTBP', 'description' => 'Real Property'],

            // Payment Order
            ['name' => 'Online Payment Order', 'code' => 'OPO', 'description' => 'Payment Order'],

            // Building and Construction
            ['name' => 'Building Permit Requirements', 'code' => 'BPR', 'description' => 'Building and Construction'],
            ['name' => 'Certificate of Occupancy Requirements', 'code' => 'COR', 'description' => 'Building and Construction'],
            ['name' => 'Application Tracking', 'code' => 'AT', 'description' => 'Building and Construction'],
            ['name' => 'Building Permit Application', 'code' => 'BPA', 'description' => 'Building and Construction'],
            ['name' => 'OSCP Online Billing and Payment', 'code' => 'OSCP', 'description' => 'Building and Construction'],
            ['name' => 'Certificate of Occupancy Application', 'code' => 'COA', 'description' => 'Building and Construction'],
            ['name' => 'Pay PTR (Professional Tax Receipt)', 'code' => 'PPTR', 'description' => 'Building and Construction'],
            ['name' => 'Register Professional', 'code' => 'RP', 'description' => 'Building and Construction'],
            ['name' => 'Update Professional', 'code' => 'UP', 'description' => 'Building and Construction'],
        ];

        foreach ($services as $svc) {
            $branch->services()->create(array_merge($svc, ['last_ticket_number' => 0]));
        }
    }
}
