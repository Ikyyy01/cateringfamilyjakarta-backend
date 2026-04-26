<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nama_pemesan',
        'no_hp',
        'order_number',
        'event_date',
        'event_address',
        'event_city',
        'distance_km',
        'total_pax',
        'subtotal',
        'delivery_fee',
        'service_fee',
        'discount',
        'total_price',
        'status',
        'notes',
        'cancelled_reason',
        'coupon_id',
    ];

    protected $casts = [
        'event_date'   => 'date',
        'subtotal'     => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'service_fee'  => 'decimal:2',
        'discount'     => 'decimal:2',
        'total_price'  => 'decimal:2',
        'distance_km'  => 'decimal:2',
    ];

    const STATUS_PENDING    = 'pending';
    const STATUS_CONFIRMED  = 'confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_DELIVERED  = 'delivered';
    const STATUS_COMPLETED  = 'completed';
    const STATUS_CANCELLED  = 'cancelled';

    // user_id nullable karena guest bisa pesan
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customMenus()
    {
        return $this->hasMany(CustomMenu::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    // Nama pemesan: dari kolom langsung atau dari relasi user
    public function getNamaPemesanDisplayAttribute()
    {
        return $this->nama_pemesan ?? $this->user?->name ?? '-';
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending'    => 'Menunggu Konfirmasi',
            'confirmed'  => 'Dikonfirmasi',
            'processing' => 'Diproses',
            'delivered'  => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
            default      => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending'    => 'warning',
            'confirmed'  => 'info',
            'processing' => 'primary',
            'delivered'  => 'secondary',
            'completed'  => 'success',
            'cancelled'  => 'danger',
            default      => 'secondary',
        };
    }

    public static function generateOrderNumber()
    {
        return DB::transaction(function () {
            $date = now()->format('Ymd');
            $last = self::withTrashed()
                ->whereDate('created_at', today())
                ->lockForUpdate()
                ->count() + 1;
            return 'CFJ-' . $date . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
        });
    }

    public function recalculateTotal()
    {
        $itemsTotal  = $this->orderItems->sum('subtotal');
        $customTotal = $this->customMenus->where('status', 'approved')->sum('subtotal');
        $this->subtotal    = $itemsTotal + $customTotal;
        $this->total_price = $this->subtotal + $this->delivery_fee + $this->service_fee - $this->discount;
        $this->save();
    }
}
