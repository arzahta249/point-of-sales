@extends('layouts.app')
@section('title', 'Edit Pelanggan')

@section('content')
<div class="mb-4">
    <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Pelanggan</h5>
</div>

<div class="card" style="max-width:500px">
    <div class="card-body">
        <form method="POST" action="{{ route('customers.update', $customer) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $customer->nama) }}"
                       class="form-control @error('nama') is-invalid @enderror"
                       placeholder="Nama lengkap pelanggan" autofocus>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">No. HP</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-phone"></i></span>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $customer->no_hp) }}"
                           class="form-control @error('no_hp') is-invalid @enderror"
                           placeholder="08xxxxxxxxxx">
                    @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i>Update
                </button>
                <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

