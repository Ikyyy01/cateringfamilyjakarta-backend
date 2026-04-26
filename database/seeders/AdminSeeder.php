<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus semua admin lama, buat ulang bersih
        DB::table('users')->where('role', 'admin')->delete();

        DB::table('users')->insert([
            'name'              => 'Admin',
            'email'             => 'admin@catering.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('admin123'),
            'role'              => 'admin',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        echo "✅ Akun admin dibuat ulang!\n";
        echo "   Email    : admin@catering.com\n";
        echo "   Password : admin123\n";
    }
}
