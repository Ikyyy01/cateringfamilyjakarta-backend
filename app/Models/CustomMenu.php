<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_name',
        'description',
        'pax',
        'estimated_price',
        'subtotal',
        'status',
        'admin_notes',
        'approved_at',
    ];

    protected $casts = [
        'estimated_price' => 'decimal:2',
        'subtotal'        => 'decimal:2',
        'approved_at'     => 'datetime',
    ];

    // Relasi: custom menu milik 1 pesanan
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scope: hanya yang pending
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope: hanya yang approved
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Label status dalam Bahasa Indonesia
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending'  => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => 'Tidak Diketahui',
        };
    }

    // Hitung subtotal setelah admin isi harga
    public function calculateSubtotal()
    {
        $this->subtotal = $this->estimated_price * $this->pax;
        return $this->subtotal;
    }
}
