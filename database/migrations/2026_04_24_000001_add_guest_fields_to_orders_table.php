<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom nama_pemesan & no_hp ke tabel orders
     * agar guest (tanpa login) bisa melakukan pemesanan.
     * user_id dijadikan nullable.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Jadikan user_id nullable — guest tidak punya akun
            $table->foreignId('user_id')->nullable()->change();

            // Field identitas pemesan (wajib diisi saat checkout)
            if (!Schema::hasColumn('orders', 'nama_pemesan')) {
                $table->string('nama_pemesan')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('orders', 'no_hp')) {
                $table->string('no_hp', 20)->nullable()->after('nama_pemesan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['nama_pemesan', 'no_hp']);
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
