<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'label',
        'value',
        'description',
    ];

    protected $casts = [
        'value' => 'decimal:2',
    ];

    // Ambil nilai config berdasarkan key
    public static function getValue(string $key, $default = 0)
    {
        $config = self::where('key', $key)->first();
        return $config ? $config->value : $default;
    }

    // Hitung ongkos kirim berdasarkan jarak
    public static function calculateDeliveryFee(float $distanceKm): float
    {
        $freeRadius = self::getValue('free_delivery_radius', 5);
        if ($distanceKm <= $freeRadius) {
            return 0;
        }
        $feePerKm = self::getValue('delivery_fee_per_km', 5000);
        return ($distanceKm - $freeRadius) * $feePerKm;
    }
}
