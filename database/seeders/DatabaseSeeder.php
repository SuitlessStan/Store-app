<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CustomerSeeder::class,
            OrderSeeder::class,
            OrderDetailSeeder::class,
            ProductSeeder::class,
            ProductSupplierSeeder::class,
            SupplierSeeder::class,
            // UserSeeder::class,
        ]);
    }
}
