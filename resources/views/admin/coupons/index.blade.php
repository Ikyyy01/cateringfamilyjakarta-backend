@extends('layouts.admin')

@section('title', 'Kelola Kupon')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Kupon & Promo</h1>
        <button onclick="document.getElementById('create-modal').classList.remove('hidden')"
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
            + Tambah Kupon
        </button>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    {{-- Coupon Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Diskon</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min. Order</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pemakaian</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Berlaku</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($coupons as $coupon)
                <tr>
                    <td class="px-4 py-3 font-mono font-bold text-gray-900">{{ $coupon->code }}</td>
                    <td class="px-4 py-3">
                        <span class="text-green-600 font-medium">{{ $coupon->discount_label }}</span>
                        <span class="text-xs text-gray-500">({{ $coupon->type }})</span>
                    </td>
                    <td class="px-4 py-3 text-sm">Rp {{ number_format($coupon->min_order, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-sm">{{ $coupon->used_count }} / {{ $coupon->usage_limit }}</td>
                    <td class="px-4 py-3 text-sm">
                        {{ $coupon->start_date->format('d/m/Y') }} - {{ $coupon->end_date->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3">
                        @if($coupon->isValid())
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-sm text-blue-600 hover:underline mr-2">
                                {{ $coupon->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline"
                              onsubmit="return confirm('Yakin ingin menghapus kupon ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">Belum ada kupon.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $coupons->links() }}</div>

    {{-- Create Modal --}}
    <div id="create-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 p-6">
            <h3 class="text-lg font-bold mb-4">Tambah Kupon Baru</h3>
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Kupon</label>
                        <input type="text" name="code" required class="w-full border rounded-lg px-3 py-2" placeholder="PROMO50" style="text-transform: uppercase;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                        <select name="type" class="w-full border rounded-lg px-3 py-2">
                            <option value="percentage">Persentase (%)</option>
                            <option value="fixed">Nominal Tetap (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Diskon</label>
                        <input type="number" name="value" required step="0.01" class="w-full border rounded-lg px-3 py-2" placeholder="10">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Min. Order (Rp)</label>
                        <input type="number" name="min_order" value="0" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maks. Diskon (Rp)</label>
                        <input type="number" name="max_discount" value="0" class="w-full border rounded-lg px-3 py-2" placeholder="0 = unlimited">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Batas Pemakaian</label>
                        <input type="number" name="usage_limit" value="100" min="1" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mulai</label>
                        <input type="date" name="start_date" required class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Berakhir</label>
                        <input type="date" name="end_date" required class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <input type="text" name="description" class="w-full border rounded-lg px-3 py-2" placeholder="Promo spesial bulan ini">
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('create-modal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
