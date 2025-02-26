<?php

namespace Database\Seeders;

use App\Models\Cashier;
use Illuminate\Database\Seeder;

class CashierSeeder extends Seeder
{
    public function run()
    {
        $cashiers = [
            [
                'user_id' => 3,
                'seller_id' => 1,
                'document_file' => 'Untitled.png',
                'is_approved' => true,
            ],
        ];

        Cashier::insert($cashiers);
    }
}
