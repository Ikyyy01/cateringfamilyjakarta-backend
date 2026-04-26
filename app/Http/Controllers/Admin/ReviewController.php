<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with('user', 'order')->latest();

        // Filter rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('order', fn($o) => $o->where('order_number', 'like', "%{$search}%"));
            });
        }

        $reviews = $query->paginate(15)->withQueryString();

        $avgRating   = Review::avg('rating');
        $totalReviews = Review::count();
        $ratingDist  = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingDist[$i] = Review::where('rating', $i)->count();
        }

        return view('admin.reviews.index', compact(
            'reviews', 'avgRating', 'totalReviews', 'ratingDist'
        ));
    }

    public function destroy(Review $review)
    {
        ActivityLog::log('deleted', $review, 'Review dari "' . ($review->user->name ?? 'Unknown') . '" dihapus.');

        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review berhasil dihapus.');
    }
}
