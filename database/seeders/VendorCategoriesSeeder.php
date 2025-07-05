<?php

namespace Database\Seeders;

use App\Models\VendorCategories;
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
        $vendor_category = [
            'E-commerces Vendor',
            'Ride-hailing Vendor',
            'Service Apartment Vendor',
            'Food Vendor',
            'Automobile Maintenance Vendor'
        ];

        foreach ($vendor_category as $category) {
            VendorCategories::firstOrCreate(
                [
                    'name' => $category,
                    'has_custom_fields' => false,
                ],
            );
        }
    }
}
