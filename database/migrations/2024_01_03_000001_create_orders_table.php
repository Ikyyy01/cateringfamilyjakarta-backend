<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->date('event_date');
            $table->text('event_address');
            $table->string('event_city');
            $table->decimal('distance_km', 8, 2)->default(0);
            $table->integer('total_pax')->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('delivery_fee', 12, 2)->default(0);
            $table->decimal('service_fee', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->enum('status', [
                'pending', 'confirmed', 'processing', 'delivered', 'completed', 'cancelled'
            ])->default('pending');
            $table->text('notes')->nullable();
            $table->text('cancelled_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
