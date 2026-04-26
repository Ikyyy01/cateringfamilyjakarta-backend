<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            PriceConfigSeeder::class,
            CategorySeeder::class,
            MenuSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
