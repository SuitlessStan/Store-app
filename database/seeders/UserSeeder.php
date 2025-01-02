<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Alice Johnson',
                'email' => 'alice@example.com',
                'password' => Hash::make('passWORD123'),
                'is_admin' => false,
                'role' => 'user',
            ],
            [
                'name' => 'Bob Brown',
                'email' => 'bob@example.com',
                'password' => Hash::make('PassWord123'),
                'is_admin' => false,
                'role' => 'user',
            ],
            [
                'name' => 'Ahmad',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'role' => 'admin',
            ],
        ]);
    }
}
