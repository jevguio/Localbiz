<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    public function run()
    {
        $couriers = [
            ['name' => 'NinjaVan'],
            ['name' => 'Shopee Express'],
            ['name' => 'LBC'],
            ['name' => 'Flash Express'],
            ['name' => 'J&T Express']
        ];

        Courier::insert($couriers);
    }
}

