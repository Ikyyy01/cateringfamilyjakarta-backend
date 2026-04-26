<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin utama
        User::updateOrCreate(
            ['email' => 'admin@cateringfamilyjakarta.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'phone'    => '08123456789',
                'address'  => 'Jakarta, Indonesia',
            ]
        );

        // Customer demo
        User::firstOrCreate(
            ['email' => 'customer@demo.com'],
            [
                'name'     => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role'     => 'customer',
                'phone'    => '08198765432',
                'address'  => 'Jakarta Selatan',
            ]
        );
    }
}
