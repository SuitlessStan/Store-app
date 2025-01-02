<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            [
                'name' => 'Product 1',
                'description' => 'Description for Product 1',
                'price' => 250,
                'category' => 'Electronics',
                'stock_quantity' => 10,
                'product_image' => 'path/to/image1.jpg',
            ],
            [
                'name' => 'Product 2',
                'description' => 'Description for Product 2',
                'price' => 200,
                'category' => 'Books',
                'stock_quantity' => 20,
                'product_image' => 'path/to/image2.jpg',
            ],
        ]);
    }
}
