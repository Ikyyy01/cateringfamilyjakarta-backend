<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $nasiBox  = Category::where('slug', 'nasi-box')->first();
        $aqiqah   = Category::where('slug', 'aqiqah')->first();
        $prasman  = Category::where('slug', 'prasmanan')->first();
        $snack    = Category::where('slug', 'snack-box')->first();

        $menus = [
            // ── Nasi Box ──
            [
                'category_id' => $nasiBox?->id,
                'name'        => 'Nasi Box Ayam Goreng',
                'description' => 'Nasi putih pulen dengan ayam goreng renyah, tempe orek, lalapan segar, dan sambal pedas. Dilengkapi kerupuk dan buah.',
                'price'       => 35000,
                'min_pax'     => 20,
                'max_pax'     => 2000,
                'is_active'   => true,
            ],
            [
                'category_id' => $nasiBox?->id,
                'name'        => 'Nasi Box Ayam Bakar',
                'description' => 'Nasi putih dengan ayam bakar kecap, plecing kangkung, tempe goreng, dan sambal matah. Cocok untuk semua acara.',
                'price'       => 38000,
                'min_pax'     => 20,
                'max_pax'     => 2000,
                'is_active'   => true,
            ],
            [
                'category_id' => $nasiBox?->id,
                'name'        => 'Nasi Box Rendang',
                'description' => 'Nasi putih dengan rendang daging sapi pilihan yang gurih dan kaya rempah, sayur balado, dan perkedel.',
                'price'       => 45000,
                'min_pax'     => 20,
                'max_pax'     => 2000,
                'is_active'   => true,
            ],
            [
                'category_id' => $nasiBox?->id,
                'name'        => 'Nasi Box Ikan Bakar',
                'description' => 'Nasi putih dengan ikan bakar bumbu kecap pedas, urap sayur segar, dan sambal terasi khas rumahan.',
                'price'       => 40000,
                'min_pax'     => 20,
                'max_pax'     => 2000,
                'is_active'   => true,
            ],
            [
                'category_id' => $nasiBox?->id,
                'name'        => 'Nasi Box Komplit Premium',
                'description' => 'Nasi dengan 2 lauk pilihan (ayam + daging), sayur, perkedel, kerupuk, buah, dan minuman botol.',
                'price'       => 55000,
                'min_pax'     => 20,
                'max_pax'     => 2000,
                'is_active'   => true,
            ],

            // ── Aqiqah ──
            [
                'category_id' => $aqiqah?->id,
                'name'        => 'Paket Aqiqah 1 Kambing',
                'description' => 'Kambing aqiqah 1 ekor sudah termasuk proses penyembelihan, memasak, dan penyajian. Cocok untuk aqiqah anak perempuan.',
                'price'       => 120000,
                'min_pax'     => 30,
                'max_pax'     => 100,
                'is_active'   => true,
            ],
            [
                'category_id' => $aqiqah?->id,
                'name'        => 'Paket Aqiqah 2 Kambing',
                'description' => 'Kambing aqiqah 2 ekor sudah termasuk proses penyembelihan, memasak, dan penyajian. Cocok untuk aqiqah anak laki-laki.',
                'price'       => 110000,
                'min_pax'     => 60,
                'max_pax'     => 200,
                'is_active'   => true,
            ],

            // ── Prasmanan ──
            [
                'category_id' => $prasman?->id,
                'name'        => 'Prasmanan Paket Silver',
                'description' => 'Nasi putih, 2 pilihan lauk, 1 sayur, kerupuk, dan minuman. Termasuk peralatan makan dan pramusaji.',
                'price'       => 65000,
                'min_pax'     => 50,
                'max_pax'     => 500,
                'is_active'   => true,
            ],
            [
                'category_id' => $prasman?->id,
                'name'        => 'Prasmanan Paket Gold',
                'description' => 'Nasi putih + nasi goreng, 3 pilihan lauk, 2 sayur, buah, dessert, dan minuman. Termasuk peralatan makan, dekorasi meja, dan pramusaji.',
                'price'       => 85000,
                'min_pax'     => 100,
                'max_pax'     => 1000,
                'is_active'   => true,
            ],

            // ── Snack Box ──
            [
                'category_id' => $snack?->id,
                'name'        => 'Snack Box Meeting',
                'description' => 'Paket snack box berisi 3 jenis kue tradisional, risoles, kroket, dan minuman botol 330ml.',
                'price'       => 18000,
                'min_pax'     => 20,
                'max_pax'     => 1000,
                'is_active'   => true,
            ],
            [
                'category_id' => $snack?->id,
                'name'        => 'Snack Box Premium',
                'description' => 'Paket snack box premium berisi roti sandwich, 2 kue basah, 1 kue kering, dan minuman botol. Cocok untuk seminar dan workshop.',
                'price'       => 25000,
                'min_pax'     => 20,
                'max_pax'     => 1000,
                'is_active'   => true,
            ],
        ];

        foreach ($menus as $menu) {
            if ($menu['category_id']) {
                Menu::firstOrCreate(
                    ['name' => $menu['name'], 'category_id' => $menu['category_id']],
                    $menu
                );
            }
        }
    }
}
