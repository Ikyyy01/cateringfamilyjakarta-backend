<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Belum login → redirect ke halaman login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Sudah login tapi bukan admin → redirect ke beranda dengan pesan error
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Akses ditolak. Halaman ini hanya untuk admin.');
        }

        return $next($request);
    }
}
