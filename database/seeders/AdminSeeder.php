<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus semua admin termasuk soft deleted
        DB::table('users')->where('role', 'admin')->delete();

        // Password di-hash manual, TIDAK lewat model (hindari double hash dari cast)
        $hashed = Hash::make('admin123');

        DB::table('users')->insert([
            'name'              => 'Admin',
            'email'             => 'admin@catering.com',
            'email_verified_at' => now(),
            'password'          => $hashed,
            'role'              => 'admin',
            'remember_token'    => null,
            'deleted_at'        => null,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        echo "=================================\n";
        echo "✅ Akun admin berhasil dibuat!\n";
        echo "   Email    : admin@catering.com\n";
        echo "   Password : admin123\n";
        echo "=================================\n";
    }
}
