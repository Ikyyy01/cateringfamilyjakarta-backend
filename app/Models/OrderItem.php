<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_id',
        'menu_name',
        'price',
        'pax',
        'subtotal',
    ];

    protected $casts = [
        'price'    => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relasi: item milik 1 pesanan
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi: item merujuk ke 1 menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // Hitung subtotal otomatis
    public function calculateSubtotal()
    {
        $this->subtotal = $this->price * $this->pax;
        return $this->subtotal;
    }
}
