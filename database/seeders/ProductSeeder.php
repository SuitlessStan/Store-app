<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            ['name' => 'Laptop', 'description' => 'Gaming laptop', 'category' => 'Electronics', 'brand' => 'Dell', 'price' => 1000, 'stock_quantity' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Phone', 'description' => 'Smartphone with 128GB storage', 'category' => 'Electronics', 'brand' => 'Samsung', 'price' => 800, 'stock_quantity' => 20, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
