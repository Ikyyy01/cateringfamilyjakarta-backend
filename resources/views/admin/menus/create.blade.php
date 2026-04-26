@extends('layouts.admin')
@section('title', 'Tambah Menu')
@section('page-title', 'Tambah Menu Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <a href="{{ route('admin.menus.index') }}" class="btn btn-sm btn-light rounded-pill">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="fw-700">Form Tambah Menu</span>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-600">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="Contoh: Nasi Box Ayam Goreng" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-600">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select rounded-3 @error('category_id') is-invalid @enderror" required>
                                <option value="">— Pilih —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600">Deskripsi</label>
                            <textarea name="description" rows="3" class="form-control rounded-3 @error('description') is-invalid @enderror"
                                      placeholder="Isi menu, bahan-bahan, keterangan…">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-600">Harga per Pax (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control rounded-3 @error('price') is-invalid @enderror"
                                   value="{{ old('price') }}" min="0" placeholder="35000" required>
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-600">Min. Pax <span class="text-danger">*</span></label>
                            <input type="number" name="min_pax" class="form-control rounded-3 @error('min_pax') is-invalid @enderror"
                                   value="{{ old('min_pax', 10) }}" min="1" required>
                            @error('min_pax')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-600">Maks. Pax <span class="text-danger">*</span></label>
                            <input type="number" name="max_pax" class="form-control rounded-3 @error('max_pax') is-invalid @enderror"
                                   value="{{ old('max_pax', 1000) }}" min="1" required>
                            @error('max_pax')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-600">Foto Menu</label>
                            <input type="file" name="image" class="form-control rounded-3 @error('image') is-invalid @enderror"
                                   accept="image/*" onchange="previewImg(this)">
                            <div class="form-text">Maks. 2MB. Format: JPG, PNG, WEBP.</div>
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <img id="imgPreview" src="" alt="" class="rounded-3 d-none"
                                 style="width:100px;height:100px;object-fit:cover">
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                       id="isActive" {{ old('is_active', 1) ? 'checked' : '' }}>
                                <label class="form-check-label fw-600" for="isActive">Menu Aktif (ditampilkan ke pelanggan)</label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-700">
                            <i class="bi bi-check-lg me-1"></i>Simpan Menu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImg(input) {
    const preview = document.getElementById('imgPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.classList.remove('d-none'); };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
