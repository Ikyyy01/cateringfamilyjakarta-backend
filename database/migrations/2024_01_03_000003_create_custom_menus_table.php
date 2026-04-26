<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->integer('pax')->default(1);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->decimal('estimated_price', 12, 2)->nullable(); // harga per pax setelah admin approve
            $table->decimal('subtotal', 12, 2)->nullable();        // estimated_price * pax
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_menus');
    }
};
