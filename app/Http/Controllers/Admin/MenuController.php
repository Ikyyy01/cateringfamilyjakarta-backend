<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Menu;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category')->latest()->paginate(10);
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.menus.create', compact('categories'));
    }

    public function store(StoreMenuRequest $request)
    {
        $data              = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu = Menu::create($data);

        ActivityLog::log('created', $menu, 'Menu "' . $menu->name . '" ditambahkan.');

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit(Menu $menu)
    {
        $categories = Category::active()->get();
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $data              = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($menu->image) Storage::disk('public')->delete($menu->image);
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $oldValues = $menu->only(['name', 'price', 'is_active']);
        $menu->update($data);

        ActivityLog::log('updated', $menu, 'Menu "' . $menu->name . '" diperbarui.', $oldValues, $data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        ActivityLog::log('deleted', $menu, 'Menu "' . $menu->name . '" dihapus (soft delete).');

        // Soft delete — gambar tetap disimpan untuk recovery
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus!');
    }
}
