<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Owner
        User::create([
            'name' => 'Owner Cleansetz',
            'email' => 'owner@cleansetz.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'phone' => '081200000001',
            'address' => 'Jl. Owner 1',
        ]);

        // 2. Admin
        User::create([
            'name' => 'Admin Cleansetz',
            'email' => 'admin@cleansetz.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081200000002',
            'address' => 'Jl. Admin 2',
        ]);

        // 3. Customer
        User::create([
            'name' => 'Customer Satu',
            'email' => 'customer@cleansetz.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'phone' => '081200000003',
            'address' => 'Jl. Customer 3',
        ]);
        // 4. Specific Test Customer
        User::create([
            'name' => 'Bayu Customer',
            'email' => 'bayu@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'phone' => '085600267104',
            'address' => 'Jl. Merdeka No. 45, Jakarta',
        ]);
    }
}
