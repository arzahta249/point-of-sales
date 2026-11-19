@extends('layouts.app')
@section('title', 'Tambah Produk')

@section('content')
<div class="mb-4">
    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h5 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Produk</h5>
</div>

<div class="card" style="max-width:640px">
    <div class="card-body">
        <form method="POST" action="{{ route('products.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">SKU <span class="text-danger">*</span></label>
                    <input type="text" name="sku" value="{{ old('sku') }}"
                           class="form-control @error('sku') is-invalid @enderror"
                           placeholder="Contoh: PRD-001">
                    @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                           class="form-control @error('nama') is-invalid @enderror"
                           placeholder="Nama produk">
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Harga Beli <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_beli" value="{{ old('harga_beli') }}"
                               class="form-control @error('harga_beli') is-invalid @enderror"
                               placeholder="0" min="0" step="100">
                        @error('harga_beli') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Harga Jual <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_jual" value="{{ old('harga_jual') }}"
                               class="form-control @error('harga_jual') is-invalid @enderror"
                               placeholder="0" min="0" step="100">
                        @error('harga_jual') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Stok Awal <span class="text-danger">*</span></label>
                    <input type="number" name="stok" value="{{ old('stok', 0) }}"
                           class="form-control @error('stok') is-invalid @enderror"
                           placeholder="0" min="0">
                    @error('stok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Stok Minimum <span class="text-danger">*</span></label>
                    <input type="number" name="stok_minimum" value="{{ old('stok_minimum', 5) }}"
                           class="form-control @error('stok_minimum') is-invalid @enderror"
                           placeholder="5" min="0">
                    @error('stok_minimum') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Peringatan stok menipis akan muncul jika stok ≤ nilai ini.</div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Simpan
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

