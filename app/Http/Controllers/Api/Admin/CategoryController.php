<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // GET /api/v1/admin/categories
    public function index(Request $request)
    {
        $query = Category::withCount('menus')->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', (bool) $request->is_active);
        }

        $categories = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }

    // POST /api/v1/admin/categories
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active'   => 'boolean',
        ]);

        $data              = $request->except('image');
        $data['slug']      = Str::slug($request->name);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create($data);

        ActivityLog::log('created', $category, 'Kategori "' . $category->name . '" ditambahkan.');

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan.',
            'data'    => $category,
        ], 201);
    }

    // GET /api/v1/admin/categories/{category}
    public function show(Category $category)
    {
        return response()->json([
            'success' => true,
            'data'    => $category->loadCount('menus'),
        ]);
    }

    // PUT /api/v1/admin/categories/{category}
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active'   => 'boolean',
        ]);

        $oldValues         = $category->only(['name', 'is_active']);
        $data              = $request->except('image');
        $data['slug']      = Str::slug($request->name);
        $data['is_active'] = $request->boolean('is_active', $category->is_active);

        if ($request->hasFile('image')) {
            if ($category->image) Storage::disk('public')->delete($category->image);
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        ActivityLog::log('updated', $category, 'Kategori "' . $category->name . '" diperbarui.', $oldValues, $data);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui.',
            'data'    => $category->fresh()->loadCount('menus'),
        ]);
    }

    // DELETE /api/v1/admin/categories/{category}
    public function destroy(Category $category)
    {
        // Cek apakah ada menu yang pakai kategori ini
        if ($category->menus()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak bisa dihapus karena masih memiliki ' . $category->menus()->count() . ' menu.',
            ], 422);
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        ActivityLog::log('deleted', $category, 'Kategori "' . $category->name . '" dihapus.');
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus.',
        ]);
    }
}
