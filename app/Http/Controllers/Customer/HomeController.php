<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories    = Category::active()->withCount('menus')->get();
        $featuredMenus = Menu::active()->with('category')->inRandomOrder()->take(6)->get();

        // Reviews untuk testimonial section
        $latestReviews = Review::with('user', 'order')
            ->where('rating', '>=', 4)
            ->latest()
            ->take(6)
            ->get();
        $avgRating    = Review::avg('rating') ?? 0;
        $totalReviews = Review::count();

        return view('customer.home', compact(
            'categories', 'featuredMenus', 'latestReviews', 'avgRating', 'totalReviews'
        ));
    }

    public function about()
    {
        return view('customer.about');
    }

    public function menu(Request $request)
    {
        $categories = Category::active()->get();

        $query = Menu::active()->with('category');

        // Filter by kategori slug
        if ($request->filled('kategori')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        // Filter by search
        if ($request->filled('cari')) {
            $query->where('name', 'like', '%' . $request->cari . '%');
        }

        $menus = $query->paginate(12)->withQueryString();

        return view('customer.menu', compact('categories', 'menus'));
    }

    public function menuDetail(Menu $menu)
    {
        abort_if(!$menu->is_active, 404);
        $relatedMenus = Menu::active()
            ->where('category_id', $menu->category_id)
            ->where('id', '!=', $menu->id)
            ->take(3)
            ->get();
        return view('customer.menu-detail', compact('menu', 'relatedMenus'));
    }
}
