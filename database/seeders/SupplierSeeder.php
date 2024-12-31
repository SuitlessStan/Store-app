<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::insert([
            ['name' => 'Tech Supplies', 'contact_person' => 'John Doe', 'phone' => '123-456-7890', 'address' => '123 Tech St', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gadget World', 'contact_person' => 'Jane Smith', 'phone' => '987-654-3210', 'address' => '456 Gadget Ave', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
