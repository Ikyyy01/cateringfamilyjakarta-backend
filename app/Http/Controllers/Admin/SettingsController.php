<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceConfig;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $configs = PriceConfig::all();
        return view('admin.settings.index', compact('configs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'configs'   => 'required|array',
            'configs.*' => 'required|numeric|min:0',
        ]);

        foreach ($request->configs as $key => $value) {
            PriceConfig::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan harga berhasil disimpan!');
    }
}
