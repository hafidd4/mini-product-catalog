<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and gadgets'],
            ['name' => 'Clothing', 'description' => 'Apparel and fashion items'],
            ['name' => 'Books', 'description' => 'Books and educational materials'],
            ['name' => 'Home & Garden', 'description' => 'Home improvement and garden items'],
            ['name' => 'Sports', 'description' => 'Sports equipment and accessories'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
