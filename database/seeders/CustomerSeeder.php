<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::insert([
            ['user_id' => 1, 'name' => 'Alice Johnson', 'phone' => '1234567890', 'address' => '123 Main St', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 2, 'name' => 'Bob Brown', 'phone' => '0987654321', 'address' => '456 Elm St', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
