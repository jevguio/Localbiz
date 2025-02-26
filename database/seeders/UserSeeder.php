<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'address' => 'Manila, Philippines',
                'phone' => '09123456789',
                'role' => 'Owner',
                'is_active' => true,
                'last_login' => now(),
            ],
            [
                'name' => 'Seller',
                'email' => 'seller@gmail.com',
                'password' => Hash::make('password'),
                'address' => 'Manila, Philippines',
                'phone' => '09123456789',
                'avatar' => 'avatar.png',
                'gcash_number' => '09123456789',
                'bank_name' => 'BDO',
                'bank_account_number' => '1234567890',
                'role' => 'Seller',
                'is_active' => true,
                'last_login' => now(),
            ],
            [
                'name' => 'Cashier',
                'email' => 'cashier@gmail.com',
                'password' => Hash::make('password'),
                'address' => 'Manila, Philippines',
                'phone' => '09123456789',
                'role' => 'Cashier',
                'is_active' => true,
                'last_login' => now(),
            ],
            [
                'name' => 'GovernmentAgency',
                'email' => 'governmentagency@gmail.com',
                'password' => Hash::make('password'),
                'address' => 'Manila, Philippines',
                'phone' => '09123456789',
                'role' => 'GovernmentAgency',
                'is_active' => true,
                'last_login' => now(),
            ],
            [
                'name' => 'DeliveryRider',
                'email' => 'deliveryrider@gmail.com',
                'password' => Hash::make('password'),
                'address' => 'Manila, Philippines',
                'phone' => '09123456789',
                'role' => 'DeliveryRider',
                'is_active' => true,
                'last_login' => now(),
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@gmail.com',
                'password' => Hash::make('password'),
                'address' => 'Manila, Philippines',
                'phone' => '09123456789',
                'role' => 'Customer',
                'is_active' => true,
                'last_login' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
