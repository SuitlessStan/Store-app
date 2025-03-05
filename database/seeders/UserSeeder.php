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
                'name' => 'Ahmad',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'role' => 'admin',
            ],
            [
                'name' => 'Ahmad',
                'email' => 'ahmad@admin.com',
                'password' => Hash::make('ahmad@ahmad'),
                'is_admin' => true,
                'role' => 'admin',
            ],
        ]);
    }
}
