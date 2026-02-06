<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@hotel.com'],
            [
                'name' => 'مسؤول النظام',
                'password' => 'Admin@123456',
                'is_admin' => true,
            ]
        );
    }
}

