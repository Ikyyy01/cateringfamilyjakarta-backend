<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string | image | boolean
            $table->string('label');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed default settings
        DB::table('app_settings')->insert([
            [
                'key'         => 'qris_image',
                'value'       => null,
                'type'        => 'image',
                'label'       => 'Gambar QRIS',
                'description' => 'Upload gambar QR Code QRIS untuk halaman pembayaran',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'whatsapp_number',
                'value'       => '6281234567890',
                'type'        => 'string',
                'label'       => 'Nomor WhatsApp',
                'description' => 'Nomor WA yang ditampilkan di halaman pembayaran (format: 628xxx)',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
