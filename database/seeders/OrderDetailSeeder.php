<?php

namespace Database\Seeders;

use App\Models\OrderDetail;
use Illuminate\Database\Seeder;

class OrderDetailSeeder extends Seeder
{
    public function run(): void
    {
        OrderDetail::insert([
            ['order_id' => 1, 'product_id' => 1, 'quantity' => 2, 'price' => 250, 'created_at' => now(), 'updated_at' => now()],
            ['order_id' => 2, 'product_id' => 2, 'quantity' => 1, 'price' => 200, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
