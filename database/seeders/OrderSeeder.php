<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        Order::insert([
            ['customer_id' => 1, 'order_date' => now(), 'total_amount' => 500, 'status' => 'completed', 'delivery_address' => '123 Main St', 'is_home_delivery' => true, 'created_at' => now(), 'updated_at' => now()],
            ['customer_id' => 2, 'order_date' => now(), 'total_amount' => 200, 'status' => 'pending', 'delivery_address' => '456 Elm St', 'is_home_delivery' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
