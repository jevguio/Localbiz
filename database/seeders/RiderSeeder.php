<?php

namespace Database\Seeders;

use App\Models\Rider;
use Illuminate\Database\Seeder;

class RiderSeeder extends Seeder
{
    public function run()
    {
        $riders = [
            [
                'user_id' => 5,
                'seller_id' => 1,
                'document_file' => 'Untitled.png',
                'is_approved' => true,
            ],
        ];


        Rider::insert($riders);
    }
}
