@extends('layouts.admin')
@section('title', 'Activity Log')
@section('page-title', 'Activity Log')

@section('content')

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('admin.activity-logs.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-600 mb-1">Cari</label>
                <input type="text" name="search" class="form-control form-control-sm rounded-3"
                       placeholder="Cari deskripsi / user..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-600 mb-1">Aksi</label>
                <select name="action" class="form-select form-select-sm rounded-3">
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $act)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-600 mb-1">Dari</label>
                <input type="date" name="from" class="form-control form-control-sm rounded-3"
                       value="{{ request('from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-600 mb-1">Sampai</label>
                <input type="date" name="to" class="form-control form-control-sm rounded-3"
                       value="{{ request('to') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-danger rounded-3 px-3">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                @if(request()->hasAny(['search', 'action', 'from', 'to']))
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-outline-secondary rounded-3">
                        <i class="bi bi-x-lg"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Timeline --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-2"></i>Riwayat Aktivitas</span>
        <span class="text-muted" style="font-size:.8rem">{{ $logs->total() }} log</span>
    </div>
    <div class="card-body p-0">
        @forelse($logs as $log)
            <div class="d-flex gap-3 p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                {{-- Icon --}}
                <div class="flex-shrink-0">
                    @php
                        $iconMap = [
                            'created'           => ['bi-plus-circle-fill', '#10B981'],
                            'updated'           => ['bi-pencil-fill', '#3B82F6'],
                            'deleted'           => ['bi-trash-fill', '#EF4444'],
                            'status_changed'    => ['bi-arrow-repeat', '#8B5CF6'],
                            'payment_verified'  => ['bi-check-circle-fill', '#10B981'],
                            'payment_rejected'  => ['bi-x-circle-fill', '#EF4444'],
                            'approved'          => ['bi-hand-thumbs-up-fill', '#10B981'],
                            'rejected'          => ['bi-hand-thumbs-down-fill', '#EF4444'],
                        ];
                        $icon  = $iconMap[$log->action][0] ?? 'bi-record-circle';
                        $color = $iconMap[$log->action][1] ?? '#6B7280';
                    @endphp
                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                         style="width:36px;height:36px;background:{{ $color }}15">
                        <i class="bi {{ $icon }}" style="font-size:.9rem;color:{{ $color }}"></i>
                    </div>
                </div>

                {{-- Content --}}
                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
                        <div>
                            <span class="fw-700" style="font-size:.85rem">
                                {{ $log->user->name ?? 'System' }}
                            </span>
                            <span class="status-badge ms-2"
                                  style="background:{{ $color }}15;color:{{ $color }};font-size:.68rem">
                                {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                            </span>
                        </div>
                        <span class="text-muted" style="font-size:.72rem">
                            {{ $log->created_at->format('d M Y H:i') }}
                        </span>
                    </div>
                    <p class="mt-1 mb-0 text-muted" style="font-size:.82rem">
                        {{ $log->description }}
                    </p>

                    @if($log->old_values || $log->new_values)
                        <div class="mt-2 p-2 rounded-3" style="background:#F8FAFC;font-size:.78rem">
                            @if($log->old_values)
                                <div class="text-muted">
                                    <strong>Sebelum:</strong>
                                    @foreach($log->old_values as $key => $val)
                                        <span class="badge bg-light text-dark border me-1">{{ $key }}: {{ is_array($val) ? json_encode($val) : $val }}</span>
                                    @endforeach
                                </div>
                            @endif
                            @if($log->new_values)
                                <div class="{{ $log->old_values ? 'mt-1' : '' }}">
                                    <strong>Sesudah:</strong>
                                    @foreach($log->new_values as $key => $val)
                                        <span class="badge bg-light text-dark border me-1">{{ $key }}: {{ is_array($val) ? json_encode($val) : $val }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($log->ip_address)
                        <span class="text-muted mt-1 d-inline-block" style="font-size:.7rem">
                            <i class="bi bi-geo-alt me-1"></i>{{ $log->ip_address }}
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-clock-history" style="font-size:2.5rem;color:#E5E7EB"></i>
                <p class="text-muted mt-2" style="font-size:.88rem">Belum ada aktivitas tercatat.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Pagination --}}
@if($logs->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $logs->links() }}
    </div>
@endif

@endsection
