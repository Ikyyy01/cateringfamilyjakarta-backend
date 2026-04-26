<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // Relasi: review milik 1 pesanan
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi: review milik 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope: hanya review dengan rating tinggi
    public function scopePositive($query)
    {
        return $query->where('rating', '>=', 4);
    }
}
