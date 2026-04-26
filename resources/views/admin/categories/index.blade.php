@extends('layouts.admin')
@section('title', 'Kelola Kategori')
@section('page-title', 'Kelola Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-800 mb-0">Kategori Menu</h5>
        <p class="text-muted small mb-0">Kelola kategori untuk menu catering</p>
    </div>
    <button class="btn btn-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-2"></i>Tambah Kategori
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($categories->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th>Jumlah Menu</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                    <tr>
                        <td>
                            <div class="fw-700">{{ $cat->name }}</div>
                            <div class="text-muted small">{{ $cat->description }}</div>
                        </td>
                        <td><code class="text-danger">{{ $cat->slug }}</code></td>
                        <td><span class="badge bg-secondary rounded-pill">{{ $cat->menus_count }} menu</span></td>
                        <td>
                            @if($cat->is_active)
                                <span class="status-badge badge-completed">Aktif</span>
                            @else
                                <span class="status-badge badge-cancelled">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary rounded-pill me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $cat->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            @if($cat->menus_count == 0)
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>

                    {{-- Modal Edit --}}
                    <div class="modal fade" id="modalEdit{{ $cat->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4">
                                <div class="modal-header border-0 pb-0">
                                    <h6 class="modal-title fw-800">Edit Kategori</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.categories.update', $cat) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-700 small">Nama Kategori</label>
                                            <input type="text" name="name" class="form-control rounded-3"
                                                   value="{{ $cat->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-700 small">Deskripsi</label>
                                            <textarea name="description" class="form-control rounded-3" rows="2">{{ $cat->description }}</textarea>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active"
                                                   id="active{{ $cat->id }}" {{ $cat->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label fw-600 small" for="active{{ $cat->id }}">Aktif</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-700">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <div style="font-size:3rem">🗂️</div>
            <p class="mt-2">Belum ada kategori</p>
        </div>
        @endif
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-800">Tambah Kategori Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-700 small">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-3"
                               placeholder="Contoh: Nasi Box" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-700 small">Deskripsi</label>
                        <textarea name="description" class="form-control rounded-3" rows="2"
                                  placeholder="Deskripsi singkat kategori"></textarea>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActiveTambah" checked>
                        <label class="form-check-label fw-600 small" for="isActiveTambah">Aktif (langsung tampil)</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-700">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
