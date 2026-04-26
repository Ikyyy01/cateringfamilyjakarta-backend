<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    // GET /api/v1/admin/menus
    public function index(Request $request)
    {
        $query = Menu::with('category')->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', (bool) $request->is_active);
        }

        $menus = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $menus,
        ]);
    }

    // GET /api/v1/admin/menus/{menu}
    public function show(Menu $menu)
    {
        return response()->json([
            'success' => true,
            'data'    => $menu->load('category'),
        ]);
    }

    // POST /api/v1/admin/menus
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'min_pax'     => 'nullable|integer|min:1',
            'max_pax'     => 'nullable|integer|min:1',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active'   => 'nullable',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'min_pax'     => $request->min_pax ?? 10,
            'max_pax'     => $request->max_pax ?? null,
            'is_active'   => filter_var(
                $request->is_active ?? true,
                FILTER_VALIDATE_BOOLEAN
            ),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu = Menu::create($data);

        ActivityLog::log('created', $menu, 'Menu "' . $menu->name . '" ditambahkan.');

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan.',
            'data'    => $menu->load('category'),
        ], 201);
    }

    // PUT /api/v1/admin/menus/{menu}
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'min_pax'     => 'nullable|integer|min:1',
            'max_pax'     => 'nullable|integer|min:1',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active'   => 'nullable',
        ]);

        $oldValues = $menu->only(['name', 'price', 'is_active']);

        $data = [
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'min_pax'     => $request->min_pax ?? $menu->min_pax,
            'max_pax'     => $request->has('max_pax') ? $request->max_pax : $menu->max_pax,
            'is_active'   => filter_var(
                $request->is_active ?? $menu->is_active,
                FILTER_VALIDATE_BOOLEAN
            ),
        ];

        if ($request->hasFile('image')) {
            if ($menu->image) Storage::disk('public')->delete($menu->image);
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($data);

        ActivityLog::log('updated', $menu, 'Menu "' . $menu->name . '" diperbarui.', $oldValues, $data);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui.',
            'data'    => $menu->fresh()->load('category'),
        ]);
    }

    // DELETE /api/v1/admin/menus/{menu}
    public function destroy(Menu $menu)
    {
        ActivityLog::log('deleted', $menu, 'Menu "' . $menu->name . '" dihapus.');

        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dihapus.',
        ]);
    }
}
