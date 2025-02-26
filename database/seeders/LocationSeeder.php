<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            ['name' => 'Manila'],
            ['name' => 'Quezon City'],
            ['name' => 'Makati'],
        ];

        Location::insert($locations);
    }
}
