<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = ['fashion', 'electronics', 'home_appliances', 'books', 'toys', 'sports', 'health_beauty'];


        foreach ($category as $cat){
            ProductCategory::firstOrCreate(
                ['slug' => Str::slug($cat)],
                [
                    'name' => $cat,
                    'slug' => Str::slug($cat),
                    'parent_id' => null, // Assuming these are top-level categories
                ],
            );
        }
    }
}
