<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::insert([
            [
                'name' => 'Alice Johnson',
                'address' => '123 Main St',
                'phone' => '1234567890',
                'user_id' => 1, 
            ],
            [
                'name' => 'Bob Brown',
                'address' => '456 Elm St',
                'phone' => '0987654321',
                'user_id' => 2, 
            ],
        ]);
    }
}
