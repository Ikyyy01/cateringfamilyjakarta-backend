<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with('user:id,name', 'order:id,order_number')->latest();

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%$search%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%$search%"));
            });
        }

        $reviews    = $query->paginate(15);
        $avgRating  = Review::avg('rating') ?? 0;
        $totalCount = Review::count();
        $dist = [];
        for ($i = 5; $i >= 1; $i--) {
            $dist[$i] = Review::where('rating', $i)->count();
        }

        return response()->json([
            'success' => true,
            'data'    => $reviews,
            'meta'    => ['avg_rating' => round($avgRating, 1), 'total' => $totalCount, 'distribution' => $dist],
        ]);
    }

    public function destroy(Review $review)
    {
        ActivityLog::log('deleted', $review, 'Review dari "' . ($review->user->name ?? 'Unknown') . '" dihapus.');
        $review->delete();
        return response()->json(['success' => true, 'message' => 'Review berhasil dihapus.']);
    }
}
