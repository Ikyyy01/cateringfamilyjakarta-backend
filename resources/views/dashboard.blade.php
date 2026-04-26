<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Welcome --}}
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Halo, {{ auth()->user()->name }}! 👋</h3>
                <p class="text-gray-600 mt-1">Berikut ringkasan pesanan Anda.</p>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500">Total Pesanan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-500">Pesanan Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeOrders }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Selesai</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $completedOrders }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-red-500">
                    <p class="text-sm text-gray-500">Total Belanja</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Recent Orders --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="p-5 border-b">
                        <h4 class="font-semibold text-gray-900">Pesanan Terbaru</h4>
                    </div>
                    <div class="p-5">
                        @forelse($recentOrders as $order)
                        <div class="flex justify-between items-center py-3 {{ !$loop->last ? 'border-b' : '' }}">
                            <div>
                                <a href="{{ route('customer.orders.show', $order) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ $order->order_number }}
                                </a>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-2 py-1 text-xs rounded-full
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $order->status === 'confirmed' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                    {{ $order->status === 'delivered' ? 'bg-purple-100 text-purple-800' : '' }}
                                ">
                                    {{ $order->status_label }}
                                </span>
                                <p class="text-sm font-medium text-gray-900 mt-1">{{ $order->formatted_total }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">Belum ada pesanan.</p>
                        @endforelse

                        @if($totalOrders > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('customer.orders.index') }}" class="text-blue-600 hover:underline text-sm">
                                Lihat semua pesanan →
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Pending Payments --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="p-5 border-b">
                        <h4 class="font-semibold text-gray-900">Menunggu Pembayaran</h4>
                    </div>
                    <div class="p-5">
                        @forelse($pendingPayments as $order)
                        <div class="flex justify-between items-center py-3 {{ !$loop->last ? 'border-b' : '' }}">
                            <div>
                                <a href="{{ route('customer.payment.show', $order) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ $order->order_number }}
                                </a>
                                <p class="text-sm text-gray-500">
                                    {{ $order->payment?->status_label ?? 'Belum Bayar' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-red-600">{{ $order->formatted_total }}</p>
                                <a href="{{ route('customer.payment.show', $order) }}" class="text-xs text-blue-600 hover:underline">
                                    Bayar Sekarang →
                                </a>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">Tidak ada pembayaran yang menunggu. 🎉</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="mt-8 bg-white rounded-lg shadow p-5">
                <h4 class="font-semibold text-gray-900 mb-4">Aksi Cepat</h4>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('customer.menu') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        🍽️ Lihat Menu
                    </a>
                    <a href="{{ route('customer.cart') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        🛒 Keranjang
                    </a>
                    <a href="{{ route('customer.track') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        🔍 Lacak Pesanan
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        💬 Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
