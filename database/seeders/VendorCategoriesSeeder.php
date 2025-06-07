<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'E-commerces Vendor',
            'Ride-hailing Vendor',
            'Service Apartments',
            'Food',
            'Automobile Maintenance'
        ];

        foreach ($categories as $category){
            DB::table('categories')->updateOrInsert(
                [
            'name' => $category,
        ],
        [
            'has_custom_fields' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        }

    }
}
