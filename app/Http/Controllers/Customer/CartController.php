<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Tidak ada middleware auth — siapapun bisa pakai keranjang

    public function index()
    {
        $cart = session('cart', []);
        return view('customer.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'pax'     => 'required|integer|min:1',
        ]);

        $menu     = Menu::with('category')->findOrFail($request->menu_id);
        $isNasiBox = $menu->category && $menu->category->slug === 'nasi-box';

        // Validasi min pax hanya untuk Nasi Box
        if ($isNasiBox && $request->pax < $menu->min_pax) {
            return redirect()->back()->with('error',
                'Minimum pemesanan ' . $menu->name . ' adalah ' . $menu->min_pax . ' pax.'
            );
        }

        $cart = session('cart', []);
        $key  = 'menu_' . $menu->id;

        if (isset($cart[$key])) {
            $cart[$key]['pax']     += $request->pax;
            $cart[$key]['subtotal'] = $cart[$key]['price'] * $cart[$key]['pax'];
        } else {
            $cart[$key] = [
                'menu_id'   => $menu->id,
                'name'      => $menu->name,
                'price'     => $menu->price,
                'pax'       => $request->pax,
                'subtotal'  => $menu->price * $request->pax,
                'image'     => $menu->image,
                'category'  => $menu->category->slug ?? '',
            ];
        }

        session(['cart' => $cart]);
        return redirect()->back()->with('success', $menu->name . ' berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $key)
    {
        $request->validate([
            'pax' => 'required|integer|min:1|max:9999',
        ]);

        $cart = session('cart', []);
        if (isset($cart[$key])) {
            $cart[$key]['pax']      = $request->pax;
            $cart[$key]['subtotal'] = $cart[$key]['price'] * $request->pax;
            session(['cart' => $cart]);
        }
        return redirect()->back()->with('success', 'Keranjang diperbarui!');
    }

    public function remove($key)
    {
        $cart = session('cart', []);
        unset($cart[$key]);
        session(['cart' => $cart]);
        return redirect()->back()->with('success', 'Menu dihapus dari keranjang!');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('customer.cart')->with('success', 'Keranjang dikosongkan!');
    }
}
