@extends('layouts.app')
@section('title', 'Tambah Kategori')

@section('content')
<div class="mb-4">
    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h5 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Kategori</h5>
</div>

<div class="card" style="max-width:500px">
    <div class="card-body">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                       class="form-control @error('nama') is-invalid @enderror"
                       placeholder="Contoh: Minuman, Makanan, Elektronik" autofocus>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Simpan
                </button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

