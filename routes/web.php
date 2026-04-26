<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Frontend sudah dipindahkan ke Vue.js (http://localhost:5173).
| Laravel sekarang hanya bertugas sebagai REST API backend.
| Semua route web lama (Blade) sudah tidak digunakan.
|--------------------------------------------------------------------------
*/

// Semua akses ke domain .test diarahkan info singkat
Route::get('/{any?}', function () {
    return response()->json([
        'message' => 'Catering Family Jakarta — API Backend',
        'api'     => url('/api/v1'),
        'frontend'=> env('FRONTEND_URL', 'http://localhost:5173'),
        'docs'    => 'Akses frontend di ' . env('FRONTEND_URL', 'http://localhost:5173'),
    ]);
})->where('any', '.*');
