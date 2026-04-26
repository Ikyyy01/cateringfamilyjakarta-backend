@extends('layouts.admin')
@section('title', 'Kelola Review')
@section('page-title', 'Kelola Review')

@section('content')

{{-- Statistik --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6366F1,#818CF8)">
            <div style="font-size:.72rem;font-weight:600;opacity:.8">Total Review</div>
            <div class="mt-1" style="font-size:1.6rem;font-weight:800">{{ $totalReviews }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#F59E0B,#FBBF24)">
            <div style="font-size:.72rem;font-weight:600;opacity:.8">Rating Rata-rata</div>
            <div class="mt-1 d-flex align-items-center gap-2">
                <span style="font-size:1.6rem;font-weight:800">{{ number_format($avgRating, 1) }}</span>
                <i class="bi bi-star-fill" style="font-size:1.2rem"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body py-3">
                <div style="font-size:.75rem;font-weight:700;color:#888;margin-bottom:8px">DISTRIBUSI RATING</div>
                @foreach($ratingDist as $star => $count)
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span style="font-size:.75rem;font-weight:700;width:16px">{{ $star }}</span>
                    <i class="bi bi-star-fill" style="font-size:.7rem;color:#F59E0B"></i>
                    <div class="flex-grow-1" style="height:8px;background:#F3F4F6;border-radius:4px;overflow:hidden">
                        <div style="height:100%;width:{{ $totalReviews > 0 ? ($count/$totalReviews)*100 : 0 }}%;background:linear-gradient(90deg,#F59E0B,#FBBF24);border-radius:4px;transition:width .3s"></div>
                    </div>
                    <span style="font-size:.72rem;font-weight:600;color:#888;width:30px;text-align:right">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm rounded-3"
                       placeholder="Cari nama / komentar / no pesanan..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="rating" class="form-select form-select-sm rounded-3">
                    <option value="">Semua Rating</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                            {{ $i }} Bintang
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-danger rounded-3 px-3">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                @if(request()->hasAny(['search', 'rating']))
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-outline-secondary rounded-3">
                        <i class="bi bi-x-lg"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Daftar Review --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-chat-square-text me-2"></i>Daftar Review</span>
        <span class="text-muted" style="font-size:.8rem">{{ $reviews->total() }} review</span>
    </div>
    <div class="card-body p-0">
        @forelse($reviews as $review)
            <div class="d-flex gap-3 p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                {{-- Avatar --}}
                <div class="flex-shrink-0">
                    <div class="d-flex align-items-center justify-content-center rounded-circle fw-800 text-white"
                         style="width:40px;height:40px;background:linear-gradient(135deg,var(--cfj-primary),var(--cfj-secondary));font-size:.85rem">
                        {{ strtoupper(substr($review->user->name ?? '?', 0, 1)) }}
                    </div>
                </div>

                {{-- Content --}}
                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <span class="fw-700" style="font-size:.88rem">{{ $review->user->name ?? 'Unknown' }}</span>
                            <span class="text-muted ms-2" style="font-size:.78rem">
                                Pesanan #{{ $review->order->order_number ?? '-' }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted" style="font-size:.75rem">
                                {{ $review->created_at->diffForHumans() }}
                            </span>
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus review ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger rounded-2 border-0" title="Hapus review"
                                        style="width:28px;height:28px;padding:0;display:flex;align-items:center;justify-content:center">
                                    <i class="bi bi-trash" style="font-size:.78rem"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Stars --}}
                    <div class="mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"
                               style="font-size:.82rem;color:{{ $i <= $review->rating ? '#F59E0B' : '#E5E7EB' }}"></i>
                        @endfor
                    </div>

                    @if($review->comment)
                        <p class="mt-2 mb-0 text-muted" style="font-size:.85rem;line-height:1.6">
                            {{ $review->comment }}
                        </p>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-chat-square-text" style="font-size:2.5rem;color:#E5E7EB"></i>
                <p class="text-muted mt-2" style="font-size:.88rem">Belum ada review.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Pagination --}}
@if($reviews->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $reviews->links() }}
    </div>
@endif

@endsection
