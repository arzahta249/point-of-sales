@extends('layouts.app')
@section('title', 'Tambah Pengguna')

@section('content')
<div class="mb-4">
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h5 class="fw-bold mb-0"><i class="bi bi-person-plus me-2 text-primary"></i>Tambah Pengguna</h5>
</div>

<div class="card" style="max-width:520px">
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                       class="form-control @error('nama') is-invalid @enderror"
                       placeholder="Nama lengkap" autofocus>
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-at"></i></span>
                    <input type="text" name="username" value="{{ old('username') }}"
                           class="form-control @error('username') is-invalid @enderror"
                           placeholder="username_unik">
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-text">Hanya huruf, angka, strip (-) dan underscore (_).</div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                <select name="role" class="form-select @error('role') is-invalid @enderror">
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kasir" {{ old('role') === 'kasir' ? 'selected' : '' }}>Kasir</option>
                    <option value="pemilik" {{ old('role') === 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Minimal 6 karakter">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation"
                       class="form-control"
                       placeholder="Ulangi password">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Simpan
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

