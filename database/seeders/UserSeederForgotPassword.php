<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeederForgotPassword extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'fname' => 'Admin',
                'lname' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make(value: 'password'),
                'address' => 'Manila, Philippines',
                'phone' => '09123456789',
                'role' => 'Owner',
                'is_active' => true,
                'last_login' => now(),
            ],

        ];

        foreach ($users as $user) {
            $thisUser = User::where('email', '=', $user['email'])->first();
            if ($thisUser) {

                $thisUser->update([
                    'password' => Hash::make('password')
                ]);
            } else {

                User::create($user);
            }
        }
    }
}
