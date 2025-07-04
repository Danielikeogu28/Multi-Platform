<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IsAdmin extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.com'], 
            [
                'first_name' => 'Admin',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'last_name' => 'Admin',
                'phone' => '1234567890',
                'address' => '123 Admin Street',
                'profile_image' => 'https://example.com/default-profile-image.png',
                'username' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        Vendor::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'business_name' => 'Admin',
                'password' => bcrypt('password'),
                'phone' => '1234567890',
                'business_address' => '123 Admin Street',
                'home_address' => '123 Admin Street',
                'street' => 'Admin Street',
                'state' => 'Lagos',
                'city' => 'Lagos',
                'country' => 'Nigeria',
                'profile_image' => 'https://example.com/default-profile-image.png',
                'role' => 'admin',
                'Vendor_category_id' => $vendor_category?->id ?? 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}
