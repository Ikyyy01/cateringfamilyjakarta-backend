<?php

namespace Database\Seeders;

use App\Models\PriceConfig;
use Illuminate\Database\Seeder;

class PriceConfigSeeder extends Seeder
{
    public function run(): void
    {
        $configs = [
            [
                'key'         => 'service_fee',
                'label'       => 'Biaya Layanan',
                'value'       => 50000,
                'description' => 'Biaya layanan tetap per transaksi pemesanan',
            ],
            [
                'key'         => 'delivery_fee_per_km',
                'label'       => 'Ongkos Kirim per KM',
                'value'       => 5000,
                'description' => 'Biaya pengiriman per kilometer setelah radius gratis',
            ],
            [
                'key'         => 'free_delivery_radius',
                'label'       => 'Radius Gratis Ongkir (km)',
                'value'       => 5,
                'description' => 'Jarak dalam km yang tidak dikenakan biaya kirim',
            ],
            [
                'key'         => 'min_order_pax',
                'label'       => 'Minimum Pax Nasi Box',
                'value'       => 20,
                'description' => 'Minimum jumlah pax untuk pemesanan kategori Nasi Box',
            ],
        ];

        foreach ($configs as $config) {
            PriceConfig::firstOrCreate(
                ['key' => $config['key']],
                $config
            );
        }

        $this->command->info('✅ Price configs berhasil diseed!');
    }
}
