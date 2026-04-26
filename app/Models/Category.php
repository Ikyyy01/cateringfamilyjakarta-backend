<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi: 1 kategori punya banyak menu
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    // Hanya ambil kategori yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
