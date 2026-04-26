<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
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
        ]);

        $data = $request->all();
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);

        $coupon = Coupon::create($data);

        ActivityLog::log('created', $coupon, 'Kupon "' . $coupon->code . '" dibuat.');

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil dibuat!');
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
        ]);

        $data = $request->all();
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);

        $coupon->update($data);

        ActivityLog::log('updated', $coupon, 'Kupon "' . $coupon->code . '" diperbarui.');

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil diperbarui!');
    }

    public function destroy(Coupon $coupon)
    {
        ActivityLog::log('deleted', $coupon, 'Kupon "' . $coupon->code . '" dihapus.');
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil dihapus!');
    }

    public function toggleActive(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        $status = $coupon->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', 'Kupon berhasil ' . $status . '!');
    }
}
