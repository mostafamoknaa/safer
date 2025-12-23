<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@example.com',
                'phone' => '01234567890',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'is_active' => true,
            ],
            [
                'name' => 'فاطمة علي',
                'email' => 'fatima@example.com',
                'phone' => '01234567891',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'is_active' => true,
            ],
            [
                'name' => 'خالد أحمد',
                'email' => 'khaled@example.com',
                'phone' => '01234567892',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
