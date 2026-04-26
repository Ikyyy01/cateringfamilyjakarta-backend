<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_order',
        'max_discount',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'value'        => 'decimal:2',
        'min_order'    => 'decimal:2',
        'max_discount' => 'decimal:2',
        'start_date'   => 'date',
        'end_date'     => 'date',
        'is_active'    => 'boolean',
    ];

    // Scope: kupon yang masih aktif dan berlaku
    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereColumn('used_count', '<', 'usage_limit');
    }

    // Cek apakah kupon masih bisa dipakai
    public function isValid(): bool
    {
        return $this->is_active
            && $this->start_date <= now()
            && $this->end_date >= now()
            && $this->used_count < $this->usage_limit;
    }

    // Hitung diskon berdasarkan subtotal
    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_order) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = $subtotal * ($this->value / 100);
            return $this->max_discount > 0
                ? min($discount, $this->max_discount)
                : $discount;
        }

        // type === 'fixed'
        return min($this->value, $subtotal);
    }

    // Format label tipe diskon
    public function getDiscountLabelAttribute(): string
    {
        if ($this->type === 'percentage') {
            return number_format($this->value, 0) . '%';
        }
        return 'Rp ' . number_format($this->value, 0, ',', '.');
    }
}
