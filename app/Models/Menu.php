<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'min_pax',
        'max_pax',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relasi: menu milik 1 kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: menu bisa ada di banyak order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Hanya ambil menu yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Format harga ke Rupiah
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
