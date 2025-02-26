<?php

namespace Database\Seeders;

use App\Models\Products;
use App\Models\Seller;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'seller_id' => Seller::where('user_id', 2)->first()->id,
                'category_id' => 1,
                'name' => 'Product 1',
                'description' => 'Description 1',
                'price' => 100,
                'stock' => 10,
                'image' => 'iphone16.webp',
                'location_id' => 2,
                'is_active' => true,
            ],
            [
                'seller_id' => Seller::where('user_id', 2)->first()->id,
                'category_id' => 2,
                'name' => 'Product 2',
                'description' => 'Description 2',
                'price' => 200,
                'stock' => 20,
                'image' => 'iphone16.webp',
                'location_id' => 2,
                'is_active' => true,
            ],
            [
                'seller_id' => Seller::where('user_id', 2)->first()->id,
                'category_id' => 3,
                'name' => 'Product 3',
                'description' => 'Description 3',
                'price' => 300,
                'stock' => 30,
                'image' => 'iphone16.webp',
                'location_id' => 3,
                'is_active' => true,
            ],
        ];

        Products::insert($products);
    }
}
