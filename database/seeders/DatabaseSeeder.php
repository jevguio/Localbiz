<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SellerSeeder::class,
            RiderSeeder::class,
            CashierSeeder::class,
            CourierSeeder::class,
            CategorySeeder::class,
            LocationSeeder::class,
            ProductSeeder::class,
        ]);
    }
}