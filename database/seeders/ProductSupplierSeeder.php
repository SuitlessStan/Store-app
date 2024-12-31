<?php

namespace Database\Seeders;

use App\Models\ProductSupplier;
use Illuminate\Database\Seeder;

class ProductSupplierSeeder extends Seeder
{
    public function run(): void
    {
        ProductSupplier::insert([
            ['product_id' => 1, 'supplier_id' => 1, 'cost_price' => 900, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 2, 'supplier_id' => 2, 'cost_price' => 700, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
