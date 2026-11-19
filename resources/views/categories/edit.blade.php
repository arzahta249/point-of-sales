@extends('layouts.app')
@section('title', 'Edit Kategori')

@section('content')
<div class="mb-4">
    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Kategori</h5>
</div>

<div class="card" style="max-width:500px">
    <div class="card-body">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $category->nama) }}"
                       class="form-control @error('nama') is-invalid @enderror"
                       placeholder="Nama kategori" autofocus>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i>Update
                </button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

