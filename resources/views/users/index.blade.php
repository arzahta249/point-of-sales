@extends('layouts.app')
@section('title', 'Pengguna')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-primary"></i>Kelola Pengguna</h5>
        <small class="text-muted">Manajemen akun pengguna sistem</small>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Pengguna
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th class="text-center">Role</th>
                        <th class="text-center" width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $usr)
                    <tr>
                        <td>{{ $users->firstItem() + $loop->index }}</td>
                        <td class="fw-semibold">{{ $usr->nama }}</td>
                        <td><code>{{ $usr->username }}</code></td>
                        <td class="text-center">
                            @php
                                $colors = ['admin' => 'danger', 'kasir' => 'primary', 'pemilik' => 'warning'];
                            @endphp
                            <span class="badge bg-{{ $colors[$usr->role] ?? 'secondary' }}">
                                {{ strtoupper($usr->role) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('users.edit', $usr) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($usr->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $usr) }}" class="d-inline"
                                  onsubmit="return confirm('Hapus pengguna {{ $usr->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-people fs-2 d-block mb-2"></i>Belum ada pengguna.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-white">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection

