<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Electronics', 'seller_id' => 1],
            ['name' => 'Clothing', 'seller_id' => 1],
            ['name' => 'food', 'seller_id' => 1],
            ['name' => 'Home & Garden', 'seller_id' => 1],
        ];

        Categories::insert($categories);
    }
}
