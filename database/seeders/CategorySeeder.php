<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Nasi Box',
                'slug'        => 'nasi-box',
                'description' => 'Paket nasi box lengkap dengan lauk pilihan, cocok untuk berbagai acara.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Paket Aqiqah',
                'slug'        => 'aqiqah',
                'description' => 'Paket kambing aqiqah lengkap dengan nasi dan lauk, siap saji.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Prasmanan',
                'slug'        => 'prasmanan',
                'description' => 'Paket prasmanan lengkap untuk acara pernikahan, sunatan, dan gathering.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Snack Box',
                'slug'        => 'snack-box',
                'description' => 'Paket snack box untuk meeting, seminar, dan acara kantor.',
                'is_active'   => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
