<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $existing = DB::table('users')->where('role', 'admin')->first();

        if ($existing) {
            // Update pakai DB::table agar TIDAK kena cast 'hashed' dari model
            DB::table('users')->where('id', $existing->id)->update([
                'name'       => 'Admin',
                'email'      => 'admin@cateringfamilyjakarta.com',
                'password'   => Hash::make('admin123'),
                'updated_at' => now(),
            ]);
            echo "✅ Password admin direset. Login: admin@cateringfamilyjakarta.com / admin123\n";
        } else {
            DB::table('users')->insert([
                'name'       => 'Admin',
                'email'      => 'admin@cateringfamilyjakarta.com',
                'password'   => Hash::make('admin123'),
                'role'       => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "✅ Akun admin dibuat. Login: admin@cateringfamilyjakarta.com / admin123\n";
        }
    }
}
