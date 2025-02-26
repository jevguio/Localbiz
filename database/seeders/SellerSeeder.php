<?php

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    public function run()
    {
        $sellers = [
            [
                'user_id' => 2,
                'document_file' => 'Untitled.png',
                'logo' => 'Untitled.png',
                'is_approved' => true,
            ],
        ];

        Seller::insert($sellers);
    }
}
