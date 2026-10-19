<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Puspa Raj Niroula',
                'email' => 'Jaydurgakrishisahakari@gmail.com',
                'password' => '9815009485',
            ],
            [
                'name' => 'Ram Prasad Sharma',
                'email' => 'ram.sharma@example.com',
                'password' => 'Ram@2026Secure',
            ],
            [
                'name' => 'Sita Kumari Adhikari',
                'email' => 'sita.adhikari@example.com',
                'password' => 'Sita@2026Secure',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password']),
                ]
            );
        }
    }
}
