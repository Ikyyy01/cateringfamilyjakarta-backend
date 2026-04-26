<?php

namespace Database\Seeders;

use App\Models\CustomMenu;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // ── Buat beberapa customer dummy ──────────────────────────────
        $customers = [
            ['name' => 'Siti Rahayu',  'email' => 'siti.rahayu@gmail.com',  'phone' => '081234567891', 'address' => 'Jl. Melati No. 12, Jakarta Selatan'],
            ['name' => 'Ahmad Fauzi',  'email' => 'ahmad.fauzi@gmail.com',   'phone' => '082345678902', 'address' => 'Jl. Mawar No. 5, Jakarta Timur'],
            ['name' => 'Dewi Kusuma',  'email' => 'dewi.kusuma@gmail.com',   'phone' => '083456789013', 'address' => 'Jl. Anggrek No. 8, Jakarta Barat'],
            ['name' => 'Rina Marlina', 'email' => 'rina.marlina@gmail.com',  'phone' => '084567890124', 'address' => 'Jl. Kenanga No. 3, Jakarta Utara'],
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso2@gmail.com', 'phone' => '085678901235', 'address' => 'Jl. Dahlia No. 21, Jakarta Pusat'],
        ];

        $userModels = [];
        foreach ($customers as $c) {
            $userModels[] = User::firstOrCreate(
                ['email' => $c['email']],
                array_merge($c, ['password' => Hash::make('password'), 'role' => 'customer'])
            );
        }

        // ── Ambil menu ────────────────────────────────────────────────
        $nasiAyamGoreng  = Menu::where('name', 'like', '%Ayam Goreng%')->first();
        $nasiAyamBakar   = Menu::where('name', 'like', '%Ayam Bakar%')->first();
        $nasiRendang     = Menu::where('name', 'like', '%Rendang%')->first();
        $nasiPremium     = Menu::where('name', 'like', '%Premium%')->first();
        $aqiqah1         = Menu::where('name', 'like', '%1 Kambing%')->first();
        $aqiqah2         = Menu::where('name', 'like', '%2 Kambing%')->first();
        $prasmananSilver = Menu::where('name', 'like', '%Silver%')->first();
        $prasmananGold   = Menu::where('name', 'like', '%Gold%')->first();
        $snackMeeting    = Menu::where('name', 'like', '%Meeting%')->first();

        // ── Data pesanan dummy ────────────────────────────────────────
        $ordersData = [
            [
                'user'         => $userModels[0],
                'order_number' => 'CFJ-20260401-0001',
                'event_date'   => '2026-04-05',
                'event_address'=> 'Gedung Serbaguna RT 05, Jl. Melati No. 12, Jakarta Selatan',
                'event_city'   => 'Jakarta Selatan',
                'distance_km'  => 5,
                'notes'        => 'Tolong sajikan tepat pukul 11.00 WIB',
                'status'       => 'completed',
                'items'        => [['menu' => $nasiAyamGoreng, 'pax' => 100], ['menu' => $nasiRendang, 'pax' => 50]],
                'custom_menus' => [],
                'payment_status' => 'paid',
                'method'       => 'qris',
                'created_at'   => now()->subDays(19),
            ],
            [
                'user'         => $userModels[1],
                'order_number' => 'CFJ-20260405-0002',
                'event_date'   => '2026-04-10',
                'event_address'=> 'Aula Kelurahan Cakung, Jl. Mawar No. 5, Jakarta Timur',
                'event_city'   => 'Jakarta Timur',
                'distance_km'  => 8,
                'notes'        => '',
                'status'       => 'delivered',
                'items'        => [['menu' => $nasiAyamBakar, 'pax' => 200], ['menu' => $snackMeeting, 'pax' => 200]],
                'custom_menus' => [],
                'payment_status' => 'paid',
                'method'       => 'transfer',
                'created_at'   => now()->subDays(15),
            ],
            [
                'user'         => $userModels[2],
                'order_number' => 'CFJ-20260410-0003',
                'event_date'   => '2026-04-22',
                'event_address'=> 'Rumah Dewi, Jl. Anggrek No. 8, Jakarta Barat',
                'event_city'   => 'Jakarta Barat',
                'distance_km'  => 12,
                'notes'        => 'Aqiqah anak laki-laki, mohon disiapkan 2 kambing',
                'status'       => 'processing',
                'items'        => [['menu' => $aqiqah2, 'pax' => 80]],
                'custom_menus' => [['item_name' => 'Es Buah Segar', 'description' => 'Es buah aneka buah segar 80 porsi', 'pax' => 80, 'estimated_price' => 15000, 'status' => 'approved']],
                'payment_status' => 'pending_verification',
                'method'       => 'qris',
                'created_at'   => now()->subDays(10),
            ],
            [
                'user'         => $userModels[3],
                'order_number' => 'CFJ-20260412-0004',
                'event_date'   => '2026-04-25',
                'event_address'=> 'Balai RW 03, Jl. Kenanga No. 3, Jakarta Utara',
                'event_city'   => 'Jakarta Utara',
                'distance_km'  => 7,
                'notes'        => 'Acara sunatan, harap sajikan sebelum jam 12 siang',
                'status'       => 'confirmed',
                'items'        => [['menu' => $prasmananSilver, 'pax' => 150]],
                'custom_menus' => [],
                'payment_status' => 'paid',
                'method'       => 'transfer',
                'created_at'   => now()->subDays(8),
            ],
            [
                'user'         => $userModels[4],
                'order_number' => 'CFJ-20260415-0005',
                'event_date'   => '2026-04-28',
                'event_address'=> 'Gedung Meeting PT Abadi, Jl. Dahlia No. 21, Jakarta Pusat',
                'event_city'   => 'Jakarta Pusat',
                'distance_km'  => 3,
                'notes'        => 'Untuk rapat tahunan perusahaan',
                'status'       => 'pending',
                'items'        => [['menu' => $nasiPremium, 'pax' => 50], ['menu' => $snackMeeting, 'pax' => 50]],
                'custom_menus' => [],
                'payment_status' => 'unpaid',
                'method'       => 'transfer',
                'created_at'   => now()->subDays(5),
            ],
            [
                'user'         => $userModels[0],
                'order_number' => 'CFJ-20260408-0006',
                'event_date'   => '2026-04-12',
                'event_address'=> 'Rumah Siti, Jl. Melati No. 12, Jakarta Selatan',
                'event_city'   => 'Jakarta Selatan',
                'distance_km'  => 5,
                'notes'        => 'Acara pengajian bulanan',
                'status'       => 'completed',
                'items'        => [['menu' => $aqiqah1, 'pax' => 40]],
                'custom_menus' => [],
                'payment_status' => 'paid',
                'method'       => 'cash',
                'created_at'   => now()->subDays(12),
            ],
            [
                'user'         => $userModels[1],
                'order_number' => 'CFJ-20260418-0007',
                'event_date'   => '2026-04-30',
                'event_address'=> 'Kantor Ahmad, Jl. Mawar No. 5, Jakarta Timur',
                'event_city'   => 'Jakarta Timur',
                'distance_km'  => 10,
                'notes'        => '',
                'status'       => 'confirmed',
                'items'        => [['menu' => $prasmananGold, 'pax' => 200]],
                'custom_menus' => [['item_name' => 'Sate Kambing Bakar', 'description' => 'Sate kambing 200 tusuk', 'pax' => 200, 'estimated_price' => 10000, 'status' => 'approved']],
                'payment_status' => 'pending_verification',
                'method'       => 'qris',
                'created_at'   => now()->subDays(2),
            ],
        ];

        $serviceFee   = 50000;
        $deliveryRate = 5000;
        $freeRadius   = 5;

        foreach ($ordersData as $data) {
            $subtotal = 0;

            foreach ($data['items'] as $item) {
                if ($item['menu']) {
                    $subtotal += $item['menu']->price * $item['pax'];
                }
            }

            foreach ($data['custom_menus'] as $cm) {
                $subtotal += $cm['estimated_price'] * $cm['pax'];
            }

            $km          = $data['distance_km'];
            $deliveryFee = $km <= $freeRadius ? 0 : ($km - $freeRadius) * $deliveryRate;
            $totalPrice  = $subtotal + $deliveryFee + $serviceFee;
            $totalPax    = collect($data['items'])->sum('pax');

            $order = Order::firstOrCreate(
                ['order_number' => $data['order_number']],
                [
                    'user_id'       => $data['user']->id,
                    'event_date'    => $data['event_date'],
                    'event_address' => $data['event_address'],
                    'event_city'    => $data['event_city'],
                    'distance_km'   => $data['distance_km'],
                    'total_pax'     => $totalPax,
                    'subtotal'      => $subtotal,
                    'delivery_fee'  => $deliveryFee,
                    'service_fee'   => $serviceFee,
                    'total_price'   => $totalPrice,
                    'status'        => $data['status'],
                    'notes'         => $data['notes'],
                    'created_at'    => $data['created_at'],
                    'updated_at'    => $data['created_at'],
                ]
            );

            // Order items — tanpa kolom quantity
            foreach ($data['items'] as $item) {
                if ($item['menu']) {
                    OrderItem::firstOrCreate(
                        ['order_id' => $order->id, 'menu_id' => $item['menu']->id],
                        [
                            'menu_name' => $item['menu']->name,
                            'price'     => $item['menu']->price,
                            'pax'       => $item['pax'],
                            'subtotal'  => $item['menu']->price * $item['pax'],
                        ]
                    );
                }
            }

            // Custom menus
            foreach ($data['custom_menus'] as $cm) {
                CustomMenu::firstOrCreate(
                    ['order_id' => $order->id, 'item_name' => $cm['item_name']],
                    [
                        'description'     => $cm['description'],
                        'estimated_price' => $cm['estimated_price'],
                        'pax'             => $cm['pax'],
                        'subtotal'        => $cm['estimated_price'] * $cm['pax'],
                        'status'          => $cm['status'],
                    ]
                );
            }

            // Payment
            Payment::firstOrCreate(
                ['order_id' => $order->id],
                [
                    'amount'     => $totalPrice,
                    'method'     => $data['method'],
                    'status'     => $data['payment_status'],
                    'paid_at'    => $data['payment_status'] === 'paid' ? $data['created_at']->copy()->addDay() : null,
                    'created_at' => $data['created_at'],
                    'updated_at' => $data['created_at'],
                ]
            );
        }

        $this->command->info('✅ 7 pesanan dummy berhasil dibuat!');
    }
}
