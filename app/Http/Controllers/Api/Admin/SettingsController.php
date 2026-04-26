<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\PriceConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    // ── GET /api/v1/admin/settings ─────────────────────────────
    // Mengembalikan price_configs + app_settings dalam satu response
    public function index()
    {
        $configs     = PriceConfig::all();
        $appSettings = AppSetting::all()->keyBy('key');

        return response()->json([
            'success' => true,
            'data'    => [
                'price_configs' => $configs,
                'app_settings'  => $appSettings,
            ],
        ]);
    }

    // ── PATCH /api/v1/admin/settings ───────────────────────────
    // Update price_configs (numerik)
    public function update(Request $request)
    {
        $request->validate([
            'configs'   => 'required|array',
            'configs.*' => 'required|numeric|min:0',
        ]);

        foreach ($request->configs as $key => $value) {
            PriceConfig::where('key', $key)->update(['value' => $value]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil disimpan.',
            'data'    => PriceConfig::all(),
        ]);
    }

    // ── POST /api/v1/admin/settings/qris ───────────────────────
    // Upload gambar QRIS
    public function uploadQris(Request $request)
    {
        $request->validate([
            'qris_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Hapus gambar lama jika ada
        $existing = AppSetting::get('qris_image');
        if ($existing && Storage::disk('public')->exists($existing)) {
            Storage::disk('public')->delete($existing);
        }

        $path = $request->file('qris_image')->store('qris', 'public');

        AppSetting::set('qris_image', $path);

        return response()->json([
            'success' => true,
            'message' => 'Gambar QRIS berhasil diupload.',
            'data'    => [
                'qris_image' => $path,
                'qris_url'   => Storage::disk('public')->url($path),
            ],
        ]);
    }

    // ── DELETE /api/v1/admin/settings/qris ─────────────────────
    // Hapus gambar QRIS
    public function deleteQris()
    {
        $existing = AppSetting::get('qris_image');
        if ($existing && Storage::disk('public')->exists($existing)) {
            Storage::disk('public')->delete($existing);
        }
        AppSetting::set('qris_image', null);

        return response()->json(['success' => true, 'message' => 'Gambar QRIS berhasil dihapus.']);
    }

    // ── PATCH /api/v1/admin/settings/app ───────────────────────
    // Update app settings (string/boolean)
    public function updateApp(Request $request)
    {
        $request->validate([
            'settings'   => 'required|array',
            'settings.*' => 'nullable|string|max:500',
        ]);

        foreach ($request->settings as $key => $value) {
            AppSetting::set($key, $value);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil disimpan.',
        ]);
    }
}
