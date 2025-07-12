<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = Category::all();

        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'price' => 999.99,
                'description' => 'Latest iPhone with advanced features',
                'category_id' => $categories->where('name', 'Electronics')->first()->id,
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'price' => 899.99,
                'description' => 'Flagship Android smartphone',
                'category_id' => $categories->where('name', 'Electronics')->first()->id,
            ],
            [
                'name' => 'Nike Air Max',
                'price' => 129.99,
                'description' => 'Comfortable running shoes',
                'category_id' => $categories->where('name', 'Sports')->first()->id,
            ],
            [
                'name' => 'Levi\'s 501 Jeans',
                'price' => 79.99,
                'description' => 'Classic denim jeans',
                'category_id' => $categories->where('name', 'Clothing')->first()->id,
            ],
            [
                'name' => 'JavaScript: The Good Parts',
                'price' => 29.99,
                'description' => 'Essential JavaScript programming book',
                'category_id' => $categories->where('name', 'Books')->first()->id,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
