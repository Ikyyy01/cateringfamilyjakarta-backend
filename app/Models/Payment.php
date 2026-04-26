<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'method',
        'status',
        'proof_image',
        'bank_name',
        'account_number',
        'paid_at',
        'verified_at',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'paid_at'     => 'datetime',
        'verified_at' => 'datetime',
    ];

    // Relasi: pembayaran milik 1 pesanan
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Label status dalam Bahasa Indonesia
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'unpaid'               => 'Belum Bayar',
            'pending_verification' => 'Menunggu Verifikasi',
            'paid'                 => 'Lunas',
            'failed'               => 'Gagal',
            default                => 'Tidak Diketahui',
        };
    }

    // Label metode pembayaran
    public function getMethodLabelAttribute()
    {
        return match($this->method) {
            'transfer' => 'Transfer Bank',
            'cash'     => 'Tunai',
            'qris'     => 'QRIS',
            default    => 'Tidak Diketahui',
        };
    }

    // Format jumlah ke Rupiah
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}
