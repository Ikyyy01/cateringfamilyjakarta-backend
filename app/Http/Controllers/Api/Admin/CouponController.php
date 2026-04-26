<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::latest();
        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        return response()->json(['success' => true, 'data' => $query->paginate(15)]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'         => 'required|string|max:50|unique:coupons,code',
            'description'  => 'nullable|string|max:255',
            'type'         => 'required|in:percentage,fixed',
            'value'        => 'required|numeric|min:0',
            'min_order'    => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit'  => 'required|integer|min:1',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'is_active'    => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);

        $coupon = Coupon::create($data);
        ActivityLog::log('created', $coupon, 'Kupon "' . $coupon->code . '" dibuat.');

        return response()->json(['success' => true, 'message' => 'Kupon berhasil dibuat.', 'data' => $coupon], 201);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code'         => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'description'  => 'nullable|string|max:255',
            'type'         => 'required|in:percentage,fixed',
            'value'        => 'required|numeric|min:0',
            'min_order'    => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit'  => 'required|integer|min:1',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'is_active'    => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', $coupon->is_active);

        $coupon->update($data);
        ActivityLog::log('updated', $coupon, 'Kupon "' . $coupon->code . '" diperbarui.');

        return response()->json(['success' => true, 'message' => 'Kupon berhasil diperbarui.', 'data' => $coupon->fresh()]);
    }

    public function destroy(Coupon $coupon)
    {
        ActivityLog::log('deleted', $coupon, 'Kupon "' . $coupon->code . '" dihapus.');
        $coupon->delete();
        return response()->json(['success' => true, 'message' => 'Kupon berhasil dihapus.']);
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        $status = $coupon->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return response()->json(['success' => true, 'message' => "Kupon berhasil $status.", 'data' => $coupon->fresh()]);
    }
}
