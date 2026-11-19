@extends('layouts.app')
@section('title', 'Edit Pengguna')

@section('content')
<div class="mb-4">
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Pengguna</h5>
</div>

<div class="card" style="max-width:520px">
    <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $user->nama) }}"
                       class="form-control @error('nama') is-invalid @enderror" autofocus>
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-at"></i></span>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}"
                           class="form-control @error('username') is-invalid @enderror">
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                <select name="role" class="form-select @error('role') is-invalid @enderror">
                    <option value="admin"   {{ old('role', $user->role) === 'admin'   ? 'selected' : '' }}>Admin</option>
                    <option value="kasir"   {{ old('role', $user->role) === 'kasir'   ? 'selected' : '' }}>Kasir</option>
                    <option value="pemilik" {{ old('role', $user->role) === 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <hr>
            <p class="text-muted small mb-2">Kosongkan password jika tidak ingin mengubahnya.</p>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password Baru</label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Minimal 6 karakter">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation"
                       class="form-control"
                       placeholder="Ulangi password baru">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i>Update
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

